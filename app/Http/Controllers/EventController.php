<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::approved()->with('organiser')
            ->where('event_date', '>=', now())
            ->orderBy('event_date');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($qb) => $qb->where('title','like',"%$q%")
                ->orWhere('venue','like',"%$q%")
                ->orWhere('description','like',"%$q%"));
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        $events     = $query->paginate(12);
        $categories = Event::approved()->distinct()->pluck('category')->filter();
        return view('events.index', compact('events','categories'));
    }

    public function show(Event $event)
    {
        abort_unless($event->status === 'approved', 404);
        $event->load('organiser');
        $attendees_count = $event->paidTickets()->count();
        return view('events.show', compact('event','attendees_count'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }
}
