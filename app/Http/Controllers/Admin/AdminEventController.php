<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::with('organiser')->latest()->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('organiser','tickets.user','tickets.payment');
        return view('admin.events.show', compact('event'));
    }

    public function approve(Request $request, Event $event)
    {
        $event->update(['status' => 'approved']);
        $this->notify($event->organiser_id, 'Event Approved!',
            "Your event \"{$event->title}\" has been approved and is now live.", 'success');
        return back()->with('success', 'Event approved successfully.');
    }

    public function reject(Request $request, Event $event)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        $event->update(['status' => 'rejected', 'rejection_reason' => $request->reason]);
        $this->notify($event->organiser_id, 'Event Rejected',
            "Your event \"{$event->title}\" was rejected. Reason: {$request->reason}", 'danger');
        return back()->with('success', 'Event rejected.');
    }

    private function notify(int $userId, string $title, string $msg, string $type): void
    {
        \App\Models\Notification::create([
            'user_id' => $userId, 'title' => $title,
            'message' => $msg, 'type' => $type, 'created_at' => now()
        ]);
    }
}