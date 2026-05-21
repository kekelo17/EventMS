@extends('layouts.app')

@section('title', 'Organiser Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
    <p class="text-gray-600 mb-10">Here's an overview of your events and earnings.</p>

    <!-- Stats -->
    <div class="grid md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-8 rounded-3xl shadow">
            <p class="text-gray-500">Total Events</p>
            <p class="text-5xl font-bold mt-3">{{ $stats['total_events'] }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow">
            <p class="text-gray-500">Approved</p>
            <p class="text-5xl font-bold text-emerald-600 mt-3">{{ $stats['approved_events'] }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow">
            <p class="text-gray-500">Pending Review</p>
            <p class="text-5xl font-bold text-amber-600 mt-3">{{ $stats['pending_events'] }}</p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow">
            <p class="text-gray-500">Tickets Sold</p>
            <p class="text-5xl font-bold text-indigo-600 mt-3">{{ $stats['total_tickets'] }}</p>
        </div>
    </div>

    <!-- Wallet Summary -->
    <div class="bg-white rounded-3xl shadow p-8 mb-12">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500">Available Balance</p>
                <p class="text-4xl font-bold text-emerald-600">{{ number_format($wallet->available_balance ?? 0, 2) }} XAF</p>
            </div>
            <a href="{{ route('organiser.withdraw.create') }}" 
               class="px-8 py-4 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition">
                Withdraw Funds
            </a>
        </div>
    </div>

    <!-- Recent Events -->
    <h2 class="text-2xl font-semibold mb-6">Recent Events</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
        <div class="bg-white rounded-3xl shadow hover:shadow-lg transition p-6">
            <h3 class="font-semibold text-lg mb-2">{{ $event->title }}</h3>
            <p class="text-sm text-gray-500">{{ $event->event_date->format('d M Y') }} • {{ $event->venue }}</p>
            
            <div class="mt-6 flex justify-between items-center">
                <span class="text-xs uppercase tracking-widest 
                    {{ $event->status === 'approved' ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ $event->status }}
                </span>
                <a href="{{ route('organiser.events.show', $event) }}" 
                   class="text-indigo-600 hover:underline text-sm font-medium">
                    View →
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection