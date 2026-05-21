@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">Welcome back, {{ Auth::user()->name }} 👋</h1>

    <div class="grid md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-8 rounded-3xl shadow-sm">
            <h3 class="text-gray-500 text-sm">My Tickets</h3>
            <p class="text-5xl font-bold text-indigo-600 mt-2">{{ $tickets->count() }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm">
            <h3 class="text-gray-500 text-sm">Pending Refunds</h3>
            <p class="text-5xl font-bold text-amber-600 mt-2">{{ $pending_refunds }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm">
            <h3 class="text-gray-500 text-sm">Upcoming Events</h3>
            <p class="text-5xl font-bold text-emerald-600 mt-2">{{ $upcoming_events->count() }}</p>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-6">Your Tickets</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tickets as $ticket)
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm">
                <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                <div class="p-6">
                    <h3 class="font-semibold">{{ $ticket->event->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $ticket->event->event_date->format('d M Y') }}</p>
                    <a href="{{ route('client.ticket.show', $ticket) }}" 
                       class="mt-4 block text-center py-3 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700">
                        View Ticket
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection