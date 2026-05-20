<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Event, Payment, RefundRequest, WithdrawalRequest, Transaction};

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'          => User::count(),
            'total_events'         => Event::count(),
            'pending_events'       => Event::where('status','pending')->count(),
            'total_held'           => Payment::where('escrow_status','held')->sum('amount'),
            'total_released'       => Payment::where('escrow_status','released_to_organiser')->sum('amount'),
            'pending_refunds'      => RefundRequest::where('status','pending')->count(),
            'pending_withdrawals'  => WithdrawalRequest::where('status','pending')->count(),
            'total_revenue'        => Payment::whereNotIn('escrow_status',['refunded_to_client'])->sum('amount'),
        ];

        $recent_transactions = Transaction::latest()->limit(10)->with(['fromUser','toUser'])->get();
        $pending_events      = Event::where('status','pending')->latest()->limit(5)->with('organiser')->get();
        $pending_refunds     = RefundRequest::where('status','pending')->latest()->limit(5)->with(['user','payment.ticket.event'])->get();
        $pending_withdrawals = WithdrawalRequest::where('status','pending')->latest()->limit(5)->with('organiser')->get();

        return view('admin.dashboard', compact(
            'stats','recent_transactions','pending_events','pending_refunds','pending_withdrawals'
        ));
    }
}