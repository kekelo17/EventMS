<?php
// app/Services/EscrowService.php
namespace App\Services;

use App\Models\{Payment, Ticket, EscrowWallet, RefundRequest, WithdrawalRequest, Transaction, Notification};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * EscrowService
 * ─────────────
 * All money flows through this service.
 * Lifecycle:  Client pays → funds HELD in escrow
 *             Admin reviews → RELEASES to organiser  OR  REFUNDS to client
 *             Organiser requests withdrawal → Admin approves → marked PAID
 */
class EscrowService
{
    // ── 1. Hold payment in escrow ──────────────────────────────
    public function holdPayment(Ticket $ticket, array $gatewayData): Payment
    {
        return DB::transaction(function () use ($ticket, $gatewayData) {
            $payment = Payment::create([
                'ticket_id'       => $ticket->id,
                'user_id'         => $ticket->user_id,
                'transaction_ref' => Payment::generateRef(),
                'amount'          => $ticket->total_price,
                'currency'        => config('app.currency', 'XAF'),
                'payment_method'  => $gatewayData['method'] ?? 'card',
                'escrow_status'   => 'held',
                'card_last4'      => $gatewayData['card_last4'] ?? null,
                'card_brand'      => $gatewayData['card_brand'] ?? null,
                'gateway_response'=> $gatewayData,
            ]);

            // Update ticket
            $ticket->update(['status' => 'paid', 'paid_at' => now()]);
            $ticket->event->increment('tickets_sold', $ticket->quantity);

            // Credit organiser's escrow wallet
            $wallet = EscrowWallet::firstOrCreate(
                ['organiser_id' => $ticket->event->organiser_id],
                ['held_balance' => 0, 'available_balance' => 0, 'total_withdrawn' => 0]
            );
            $wallet->credit($ticket->total_price);

            // Transaction log
            $this->logTransaction('payment', $payment->amount, $ticket->user_id,
                $ticket->event->organiser_id, $payment->id, Payment::class,
                "Escrow hold: {$payment->transaction_ref}");

            // Notify organiser
            $this->notify($ticket->event->organiser_id,
                'New Ticket Sold',
                "A ticket for \"{$ticket->event->title}\" was purchased. Funds held in escrow.",
                'success',
                route('organiser.wallet'));

            return $payment;
        });
    }

    // ── 2. Admin releases funds to organiser ───────────────────
    public function releaseFundsToOrganiser(Payment $payment, int $adminId, string $notes = ''): bool
    {
        if (!$payment->isHeld()) {
            throw new \Exception("Payment is not in held state (current: {$payment->escrow_status}).");
        }

        return DB::transaction(function () use ($payment, $adminId, $notes) {
            $organiser = $payment->ticket->event->organiser;
            $wallet = EscrowWallet::where('organiser_id', $organiser->id)->firstOrFail();

            $payment->update([
                'escrow_status' => 'released_to_organiser',
                'released_by'   => $adminId,
                'released_at'   => now(),
                'notes'         => $notes,
            ]);

            $wallet->release($payment->amount);

            $this->logTransaction('escrow_release', $payment->amount, null,
                $organiser->id, $payment->id, Payment::class,
                "Funds released by admin #{$adminId}", $adminId);

            // Notify organiser
            $this->notify($organiser->id, 'Funds Released!',
                number_format($payment->amount, 2)." {$payment->currency} has been released to your wallet.",
                'success', route('organiser.wallet'));

            // Notify client
            $this->notify($payment->user_id, 'Payment Confirmed',
                "Your payment for \"{$payment->ticket->event->title}\" has been confirmed.",
                'info');

            return true;
        });
    }

    // ── 3. Admin refunds client ────────────────────────────────
    public function refundToClient(RefundRequest $refundRequest, int $adminId, ?float $overrideAmount = null): bool
    {
        $payment = $refundRequest->payment;

        if ($payment->isRefunded()) {
            throw new \Exception("Payment has already been refunded.");
        }

        return DB::transaction(function () use ($refundRequest, $payment, $adminId, $overrideAmount) {
            $refundAmount   = $overrideAmount ?? $refundRequest->amount_requested;
            $isPartial      = $refundAmount < $payment->amount;
            $escrowStatus   = $isPartial ? 'partial_refund' : 'refunded_to_client';

            // Update payment
            $payment->update([
                'escrow_status' => $escrowStatus,
                'refunded_by'   => $adminId,
                'refunded_at'   => now(),
                'refund_amount' => $refundAmount,
            ]);

            // Update refund request
            $refundRequest->update([
                'status'       => 'approved',
                'processed_by' => $adminId,
                'processed_at' => now(),
            ]);

            // Update ticket
            $payment->ticket->update(['status' => 'refunded']);
            $payment->ticket->event->decrement('tickets_sold', $payment->ticket->quantity);

            // Reverse from organiser wallet
            $wallet = EscrowWallet::where('organiser_id', $payment->ticket->event->organiser_id)->first();
            if ($wallet) {
                $wallet->reverseFromHeld($refundAmount);
            }

            $this->logTransaction('refund', $refundAmount, null,
                $payment->user_id, $refundRequest->id, RefundRequest::class,
                "Refund approved by admin #{$adminId}", $adminId);

            // Notify client
            $this->notify($payment->user_id, 'Refund Approved!',
                "Your refund of ".number_format($refundAmount,2)." {$payment->currency} has been approved and is being processed.",
                'success');

            // Notify organiser
            $this->notify($payment->ticket->event->organiser_id,
                'Refund Issued',
                "A refund of ".number_format($refundAmount,2)." was issued for \"{$payment->ticket->event->title}\".",
                'warning');

            return true;
        });
    }

    // ── 4. Reject refund request ──────────────────────────────
    public function rejectRefund(RefundRequest $refundRequest, int $adminId, string $note): bool
    {
        $refundRequest->update([
            'status'       => 'rejected',
            'admin_note'   => $note,
            'processed_by' => $adminId,
            'processed_at' => now(),
        ]);

        $this->notify($refundRequest->user_id, 'Refund Request Rejected',
            "Your refund request was rejected. Reason: {$note}", 'danger');

        return true;
    }

    // ── 5. Approve withdrawal ──────────────────────────────────
    public function approveWithdrawal(WithdrawalRequest $withdrawal, int $adminId, string $note = ''): bool
    {
        if ($withdrawal->status !== 'pending') {
            throw new \Exception("Withdrawal is not pending.");
        }

        return DB::transaction(function () use ($withdrawal, $adminId, $note) {
            $wallet = EscrowWallet::where('organiser_id', $withdrawal->organiser_id)->firstOrFail();

            if ($wallet->available_balance < $withdrawal->amount_requested) {
                throw new \Exception("Insufficient available balance.");
            }

            $wallet->withdraw($withdrawal->amount_requested);

            $withdrawal->update([
                'status'       => 'paid',
                'admin_note'   => $note,
                'processed_by' => $adminId,
                'processed_at' => now(),
            ]);

            $this->logTransaction('withdrawal', $withdrawal->amount_requested,
                $withdrawal->organiser_id, null,
                $withdrawal->id, WithdrawalRequest::class,
                "Withdrawal approved by admin #{$adminId}", $adminId);

            $this->notify($withdrawal->organiser_id, 'Withdrawal Approved!',
                number_format($withdrawal->amount_requested,2)." has been sent to your account.",
                'success', route('organiser.withdrawals'));

            return true;
        });
    }

    // ── 6. Reject withdrawal ───────────────────────────────────
    public function rejectWithdrawal(WithdrawalRequest $withdrawal, int $adminId, string $note): bool
    {
        $withdrawal->update([
            'status'       => 'rejected',
            'admin_note'   => $note,
            'processed_by' => $adminId,
            'processed_at' => now(),
        ]);

        $this->notify($withdrawal->organiser_id, 'Withdrawal Rejected',
            "Your withdrawal request was rejected. Reason: {$note}", 'danger');

        return true;
    }

    // ── Helpers ────────────────────────────────────────────────
    private function logTransaction(
        string $type, float $amount,
        ?int $fromUserId, ?int $toUserId,
        int $relatedId, string $relatedType,
        string $description, ?int $performedBy = null
    ): Transaction {
        return Transaction::create([
            'reference'    => 'TXN-'.strtoupper(uniqid()),
            'type'         => $type,
            'amount'       => $amount,
            'from_user_id' => $fromUserId,
            'to_user_id'   => $toUserId,
            'related_id'   => $relatedId,
            'related_type' => $relatedType,
            'status'       => 'completed',
            'description'  => $description,
            'performed_by' => $performedBy,
        ]);
    }

    private function notify(int $userId, string $title, string $message, string $type = 'info', ?string $link = null): void
    {
        Notification::create([
            'user_id'    => $userId,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'link'       => $link,
            'created_at' => now(),
        ]);
    }
}
