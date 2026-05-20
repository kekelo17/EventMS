<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Payment, Transaction};
use App\Services\EscrowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTransactionController extends Controller
{
    public function __construct(private EscrowService $escrow) {}

    public function index()
    {
        $transactions = Transaction::with(['fromUser','toUser','performedBy'])->latest()->paginate(30);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    /** Escrow dashboard: payments currently held */
    public function escrow()
    {
        $held_payments = Payment::where('escrow_status','held')
            ->with(['ticket.event','ticket.user','user'])
            ->latest()->paginate(20);

        $released = Payment::where('escrow_status','released_to_organiser')
            ->with(['ticket.event','user'])->latest()->limit(10)->get();

        $totals = [
            'held'     => Payment::where('escrow_status','held')->sum('amount'),
            'released' => Payment::where('escrow_status','released_to_organiser')->sum('amount'),
            'refunded' => Payment::whereIn('escrow_status',['refunded_to_client','partial_refund'])->sum('refund_amount'),
        ];

        return view('admin.escrow.index', compact('held_payments','released','totals'));
    }

    /** Admin releases funds from escrow to organiser */
    public function releaseFunds(Request $request, Payment $payment)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        try {
            $this->escrow->releaseFundsToOrganiser($payment, Auth::id(), $request->notes ?? '');
            return back()->with('success', "Funds of {$payment->amount} {$payment->currency} released to organiser.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
