@extends('layouts.app')

@section('title', 'EventMS - Discover Amazing Events')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900 text-white">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 py-24 md:py-32 text-center">
            <h1 class="text-6xl md:text-7xl font-bold tracking-tight mb-6">
                Discover &amp; Book<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-400 to-violet-400">Unforgettable Events</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 max-w-2xl mx-auto mb-10">
                Secure ticketing with escrow protection. 
                Find events, buy tickets safely, and support great organisers.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.index') }}" 
                   class="px-8 py-4 bg-white text-indigo-900 font-semibold rounded-xl hover:bg-gray-100 transition text-lg">
                    Browse Events
                </a>
                <a href="{{ route('register.organiser') }}" 
                   class="px-8 py-4 border-2 border-white font-semibold rounded-xl hover:bg-white/10 transition text-lg">
                    Become an Organiser
                </a>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="max-w-7xl mx-auto px-6 py-20 grid md:grid-cols-3 gap-10">
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8">
            <div class="text-4xl mb-4">🔒</div>
            <h3 class="text-2xl font-semibold mb-3">Escrow Protection</h3>
            <p class="text-gray-300">Your money stays safe until the event happens.</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8">
            <div class="text-4xl mb-4">🎟️</div>
            <h3 class="text-2xl font-semibold mb-3">Instant Tickets</h3>
            <p class="text-gray-300">Reserve and pay securely in seconds.</p>
        </div>
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8">
            <div class="text-4xl mb-4">📍</div>
            <h3 class="text-2xl font-semibold mb-3">Local Events</h3>
            <p class="text-gray-300">Discover the best events happening near you.</p>
        </div>
    </div>
</div>
@endsection