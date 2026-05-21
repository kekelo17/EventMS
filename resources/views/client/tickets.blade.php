@extends('layouts.app')

@section('title', 'My Tickets')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">My Tickets</h1>

    @if($tickets->isEmpty())
        <p class="text-gray-500">You haven't purchased any tickets yet.</p>
    @else
        <div class="grid gap-6">
            @foreach($tickets as $ticket)
                <div class="bg-white p-6 rounded-3xl shadow flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold">{{ $ticket->event->title }}</h3>
                        <p class="text-sm text-gray-500">{{ $ticket->event->venue }} • {{ $ticket->event->event_date->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-emerald-600 font-bold">{{ $ticket->quantity }} Ticket(s)</span><br>
                        <a href="{{ route('client.ticket.show', $ticket) }}" class="text-indigo-600 hover:underline">View Details →</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection