<?php

namespace App\Http\Controllers\Organiser;

use App\Http\Controllers\Controller;
use App\Models\{Event, EscrowWallet};
use Illuminate\Support\Facades\Auth;

class OrganiserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = [
            'total_events'    => Event::where('organiser_id',$user->id)->count(),
            'approved_events' => Event::where('organiser_id',$user->id)->where('status','approved')->count(),
            'pending_events'  => Event::where('organiser_id',$user->id)->where('status','pending')->count(),
            'total_tickets'   => \App\Models\Ticket::whereHas('event', fn($q)=>$q->where('organiser_id',$user->id))->where('status','paid')->count(),
        ];
        $wallet = EscrowWallet::firstOrCreate(['organiser_id'=>$user->id],['held_balance'=>0,'available_balance'=>0,'total_withdrawn'=>0]);
        $events = Event::where('organiser_id',$user->id)->latest()->limit(5)->get();
        return view('organiser.dashboard', compact('stats','wallet','events'));
    }
}
