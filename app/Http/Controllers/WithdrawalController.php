<?php

namespace App\Http\Controllers;

use App\Models\{EscrowWallet, WithdrawalRequest};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function wallet()
    {
        $wallet = EscrowWallet::firstOrCreate(
            ['organiser_id' => Auth::id()],
            ['held_balance' => 0, 'available_balance' => 0, 'total_withdrawn' => 0]
        );
        $recent_withdrawals = WithdrawalRequest::where('organiser_id', Auth::id())
            ->latest()->limit(10)->get();
        return view('organiser.wallet', compact('wallet','recent_withdrawals'));
    }

    public function create()
    {
        $wallet = EscrowWallet::where('organiser_id', Auth::id())->firstOrFail();
        return view('organiser.withdrawal-form', compact('wallet'));
    }

    public function store(Request $request)
    {
        $wallet = EscrowWallet::where('organiser_id', Auth::id())->firstOrFail();
        $request->validate([
            'amount'          => "required|numeric|min:100|max:{$wallet->available_balance}",
            'payment_channel' => 'required|in:bank,mobile_money',
            'mobile_money_number' => 'required_if:payment_channel,mobile_money',
            'bank_name'       => 'required_if:payment_channel,bank',
            'account_number'  => 'required_if:payment_channel,bank',
            'account_name'    => 'required_if:payment_channel,bank',
        ]);

        WithdrawalRequest::create([
            'organiser_id'        => Auth::id(),
            'amount_requested'    => $request->amount,
            'available_balance'   => $wallet->available_balance,
            'payment_channel'     => $request->payment_channel,
            'mobile_money_number' => $request->mobile_money_number,
            'bank_name'           => $request->bank_name,
            'account_number'      => $request->account_number,
            'account_name'        => $request->account_name,
            'status'              => 'pending',
        ]);

        return redirect()->route('organiser.withdrawals')
            ->with('success', 'Withdrawal request submitted. Admin will process it shortly.');
    }

    public function myWithdrawals()
    {
        $withdrawals = WithdrawalRequest::where('organiser_id', Auth::id())->latest()->paginate(15);
        return view('organiser.withdrawals', compact('withdrawals'));
    }
}