<?php

namespace App\Http\Controllers;

use App\Models\{Ticket, Payment};
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentController extends Controller
{
    use AuthorizesRequests;
    public function __construct(private EscrowService $escrow) {}

    /**
     * Show the simulated payment page (card / mobile money)
     */
    public function showPaymentPage(Ticket $ticket)
    {
        $this->authorize('pay', $ticket); // policy check

        if ($ticket->status !== 'reserved') {
            return redirect()->route('client.tickets')->with('error', 'This ticket is not awaiting payment.');
        }

        return view('payments.checkout', compact('ticket'));
    }

    /**
     * Process the simulated payment
     * Validates card/mobile fields, then calls EscrowService::holdPayment
     */
    public function processPayment(Request $request, Ticket $ticket)
    {
        $this->authorize('pay', $ticket);

        if ($ticket->status !== 'reserved') {
            return back()->with('error', 'Ticket already processed.');
        }

        $method = $request->input('payment_method', 'card');

        if ($method === 'card') {
            $request->validate([
                'card_number'  => 'required|digits:16',
                'card_name'    => 'required|string|max:100',
                'card_expiry'  => ['required','regex:/^\d{2}\/\d{2}$/'],
                'card_cvv'     => 'required|digits_between:3,4',
            ]);
            $gatewayData = [
                'method'     => 'card',
                'card_last4' => substr($request->card_number, -4),
                'card_brand' => $this->detectCardBrand($request->card_number),
                'card_name'  => $request->card_name,
                'simulated'  => true,
                'timestamp'  => now()->toISOString(),
            ];
        } else {
            // Mobile Money
            $request->validate([
                'mobile_number'   => 'required|string|max:20',
                'mobile_provider' => 'required|in:MTN,Orange,other',
            ]);
            $gatewayData = [
                'method'          => 'mobile_money',
                'mobile_number'   => $request->mobile_number,
                'mobile_provider' => $request->mobile_provider,
                'simulated'       => true,
                'timestamp'       => now()->toISOString(),
            ];
        }

        // Simulate a random failure (5% chance) to make it realistic
        if (rand(1, 100) <= 5) {
            $ticket->update(['status' => 'cancelled']);
            return redirect()->route('client.payment.failed', $ticket->id)
                ->with('error', 'Payment declined by the gateway. Please try again.');
        }

        try {
            $payment = $this->escrow->holdPayment($ticket, $gatewayData);
            return redirect()->route('client.payment.success', $payment->id);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: '.$e->getMessage());
        }
    }

    public function success(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) abort(403);
        $payment->load('ticket.event');
        return view('payments.success', compact('payment'));
    }

    public function failed(Ticket $ticket)
    {
        return view('payments.failed', compact('ticket'));
    }

    private function detectCardBrand(string $number): string
    {
        if (str_starts_with($number, '4'))      return 'Visa';
        if (in_array(substr($number,0,2), ['51','52','53','54','55'])) return 'Mastercard';
        if (in_array(substr($number,0,2), ['34','37'])) return 'Amex';
        return 'Unknown';
    }
}
