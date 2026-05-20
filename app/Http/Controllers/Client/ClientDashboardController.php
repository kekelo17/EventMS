<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\{Ticket, Payment, RefundRequest};
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tickets         = Ticket::where('user_id',$user->id)->with('event')->latest()->limit(5)->get();
        $pending_refunds = RefundRequest::where('user_id',$user->id)->where('status','pending')->count();
        $upcoming_events = Ticket::where('user_id',$user->id)->where('status','paid')
            ->whereHas('event', fn($q)=>$q->where('event_date','>=',now()))->with('event')->latest()->limit(3)->get();
        return view('client.dashboard', compact('tickets','pending_refunds','upcoming_events'));
    }
}
