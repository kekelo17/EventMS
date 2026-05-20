<?php

namespace App\Http\Controllers;

use App\Models\{Event, Ticket};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    use AuthorizesRequests;

    public function myTickets()
    {
        $tickets = Ticket::where('user_id', Auth::id())->with('event','payment')->latest()->paginate(12);
        return view('client.tickets', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        $ticket->load('event','payment');
        return view('client.ticket-detail', compact('ticket'));
    }

    public function reserveForm(Event $event)
    {
        abort_unless($event->status === 'approved', 404);
        if ($event->isSoldOut()) return back()->with('error', 'This event is sold out.');
        return view('client.reserve', compact('event'));
    }

    public function reserve(Request $request, Event $event)
    {
        $request->validate(['quantity' => 'required|integer|min:1|max:10']);
        abort_unless($event->status === 'approved', 404);

        $qty = $request->quantity;
        if ($event->availableSeats() < $qty) {
            return back()->with('error', "Only {$event->availableSeats()} seats remaining.");
        }

        // Cancel any unpaid reserved tickets for this user/event
        Ticket::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->where('status', 'reserved')
            ->update(['status' => 'cancelled']);

        $ticket = Ticket::create([
            'event_id'    => $event->id,
            'user_id'     => Auth::id(),
            'ticket_code' => Ticket::generateCode(),
            'quantity'    => $qty,
            'unit_price'  => $event->price,
            'total_price' => $event->price * $qty,
            'status'      => 'reserved',
            'reserved_at' => now(),
        ]);

        return redirect()->route('client.pay', $ticket->id)
            ->with('info', 'Ticket reserved! Please complete payment within 15 minutes.');
    }
}