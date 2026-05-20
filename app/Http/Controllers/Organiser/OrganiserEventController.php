<?php

namespace App\Http\Controllers\Organiser;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganiserEventController extends Controller
{
    public function index()
    {
        $events = Event::where('organiser_id', Auth::id())->latest()->paginate(15);
        return view('organiser.events.index', compact('events'));
    }

    public function create() { return view('organiser.events.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'venue'       => 'required|string|max:255',
            'event_date'  => 'required|date|after:now',
            'end_date'    => 'nullable|date|after:event_date',
            'price'       => 'required|numeric|min:0',
            'capacity'    => 'required|integer|min:1',
            'category'    => 'nullable|string|max:100',
            'banner_image'=> 'nullable|image|max:2048',
        ]);
        $data['organiser_id'] = Auth::id();
        $data['status']       = 'pending';

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('events','public');
        }

        Event::create($data);
        return redirect()->route('organiser.events')->with('success', 'Event submitted for admin review.');
    }

    public function edit(Event $event)
    {
        $this->authorizeEvent($event);
        return view('organiser.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'venue'       => 'required|string|max:255',
            'event_date'  => 'required|date',
            'price'       => 'required|numeric|min:0',
            'capacity'    => 'required|integer|min:1',
            'category'    => 'nullable|string|max:100',
        ]);
        // Re-submit for approval if editing rejected event
        if ($event->status === 'rejected') $data['status'] = 'pending';
        $event->update($data);
        return redirect()->route('organiser.events')->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $this->authorizeEvent($event);
        $event->update(['status' => 'cancelled']);
        return back()->with('success', 'Event cancelled.');
    }

    public function attendees(Event $event)
    {
        $this->authorizeEvent($event);
        $attendees = $event->paidTickets()->with('user')->paginate(20);
        return view('organiser.events.attendees', compact('event','attendees'));
    }

    private function authorizeEvent(Event $event): void
    {
        abort_unless($event->organiser_id === Auth::id(), 403);
    }
}