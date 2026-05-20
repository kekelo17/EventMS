<?php

namespace App\Http\Controllers;

use App\Models\{Payment, RefundRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundController extends Controller
{
    public function myRefunds()
    {
        $refunds = RefundRequest::where('user_id', Auth::id())
            ->with('payment.ticket.event')->latest()->paginate(10);
        return view('client.refunds', compact('refunds'));
    }

    public function create(Payment $payment)
    {
        abort_unless($payment->user_id === Auth::id(), 403);
        if ($payment->isRefunded()) return back()->with('error', 'Already refunded.');
        if ($payment->refundRequest) return back()->with('error', 'Refund already requested.');
        return view('client.refund-form', compact('payment'));
    }

    public function store(Request $request, Payment $payment)
    {
        abort_unless($payment->user_id === Auth::id(), 403);
        $request->validate(['reason' => 'required|string|min:10|max:500']);

        if ($payment->refundRequest) return back()->with('error', 'Request already submitted.');

        RefundRequest::create([
            'payment_id'       => $payment->id,
            'user_id'          => Auth::id(),
            'reason'           => $request->reason,
            'amount_requested' => $payment->amount,
            'status'           => 'pending',
        ]);

        return redirect()->route('client.refunds')->with('success', 'Refund request submitted. We will review it shortly.');
    }
}