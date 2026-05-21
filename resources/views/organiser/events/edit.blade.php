@extends('layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">Edit Event</h1>

    <div class="bg-white rounded-3xl shadow-xl p-10">
        <form method="POST" action="{{ route('organiser.events.update', $event) }}">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Event Title</label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Venue</label>
                        <input type="text" name="venue" value="{{ old('venue', $event->venue) }}" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Event Date</label>
                        <input type="datetime-local" name="event_date" 
                               value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl">
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Ticket Price (XAF)</label>
                        <input type="number" name="price" value="{{ old('price', $event->price) }}" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Capacity</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Category</label>
                        <select name="category" class="w-full px-5 py-4 border border-gray-300 rounded-2xl">
                            <option value="Music" {{ $event->category == 'Music' ? 'selected' : '' }}>Music</option>
                            <option value="Conference" {{ $event->category == 'Conference' ? 'selected' : '' }}>Conference</option>
                            <option value="Sports" {{ $event->category == 'Sports' ? 'selected' : '' }}>Sports</option>
                            <option value="Festival" {{ $event->category == 'Festival' ? 'selected' : '' }}>Festival</option>
                            <option value="Workshop" {{ $event->category == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="Other" {{ $event->category == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="6" required
                          class="w-full px-5 py-4 border border-gray-300 rounded-3xl">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="flex gap-4 mt-10">
                <button type="submit" 
                        class="flex-1 bg-indigo-600 text-white py-5 rounded-3xl font-semibold hover:bg-indigo-700 transition">
                    Update Event
                </button>
                
                <a href="{{ route('organiser.events.index') }}" 
                   class="flex-1 text-center py-5 border border-gray-300 rounded-3xl font-semibold hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection