@extends('layouts.app')

@section('title', 'Browse Events')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">Discover Events</h1>

    <!-- Search & Filter -->
    <div class="bg-white p-6 rounded-3xl shadow mb-10">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <input type="text" name="q" value="{{ request('q') }}" 
                   placeholder="Search events..." 
                   class="flex-1 px-6 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500">
            
            <button type="submit" 
                    class="px-10 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition font-medium">
                Search
            </button>
        </form>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($events as $event)
        <div class="bg-white rounded-3xl overflow-hidden shadow hover:shadow-xl transition group">
            @if($event->banner_image)
                <img src="{{ asset('storage/' . $event->banner_image) }}" 
                     class="w-full h-48 object-cover">
            @else
                <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
            @endif

            <div class="p-6">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-semibold text-xl line-clamp-2">{{ $event->title }}</h3>
                    <span class="text-emerald-600 font-bold text-xl">{{ number_format($event->price) }} XAF</span>
                </div>
                
                <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $event->description }}</p>
                
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="fas fa-calendar"></i>
                        {{ $event->event_date->format('d M Y') }}
                    </div>
                    <div class="text-gray-600">
                        <i class="fas fa-users"></i> {{ $event->availableSeats() }} left
                    </div>
                </div>

                <a href="{{ route('events.show', $event) }}" 
                   class="mt-6 block w-full text-center py-4 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition font-medium">
                    View Details & Reserve
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-10">
        {{ $events->links() }}
    </div>
</div>
@endsection