@extends('layouts.app')

@section('title', 'My Events')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">My Events</h1>
        <a href="{{ route('organiser.events.create') }}" 
           class="px-6 py-3 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 flex items-center gap-2">
            <i class="fas fa-plus"></i> Create New Event
        </a>
    </div>

    @if($events->isEmpty())
        <div class="bg-white rounded-3xl shadow p-20 text-center">
            <p class="text-6xl mb-6">🎟️</p>
            <h3 class="text-2xl font-semibold mb-3">No Events Yet</h3>
            <p class="text-gray-500 mb-8">Create your first event and start selling tickets.</p>
            <a href="{{ route('organiser.events.create') }}" 
               class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700">
                Create Your First Event
            </a>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
            <div class="bg-white rounded-3xl shadow hover:shadow-xl transition overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-indigo-500 to-violet-600"></div>
                
                <div class="p-6">
                    <div class="flex justify-between">
                        <h3 class="font-semibold text-xl line-clamp-2">{{ $event->title }}</h3>
                        <span class="text-xs uppercase tracking-widest px-3 py-1 rounded-full 
                            {{ $event->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : 
                               ($event->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                            {{ $event->status }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-gray-500 mt-2">{{ $event->event_date->format('d M Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $event->venue }}</p>

                    <div class="mt-6 flex gap-3">
                        <a href="{{ route('organiser.events.edit', $event) }}" 
                           class="flex-1 text-center py-3 border border-gray-300 rounded-2xl hover:bg-gray-50">
                            Edit
                        </a>
                        <a href="{{ route('organiser.events.attendees', $event) }}" 
                           class="flex-1 text-center py-3 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700">
                            Attendees
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection