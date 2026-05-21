@extends('layouts.app')

@section('title', 'Create New Event')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">Create New Event</h1>

    <div class="bg-white rounded-3xl shadow-xl p-10">
        <form method="POST" action="{{ route('organiser.events.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Event Title</label>
                        <input type="text" name="title" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500"
                               placeholder="e.g. Afrobeat Live Night">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Venue</label>
                        <input type="text" name="venue" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500"
                               placeholder="e.g. Palais des Congrès, Yaoundé">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Event Date & Time</label>
                        <input type="datetime-local" name="event_date" required
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">End Date (Optional)</label>
                        <input type="datetime-local" name="end_date"
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Ticket Price (XAF)</label>
                        <input type="number" name="price" required min="0" step="100"
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Capacity</label>
                        <input type="number" name="capacity" required min="1"
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500"
                               placeholder="e.g. 500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Category</label>
                        <select name="category" 
                                class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500">
                            <option value="">Select Category</option>
                            <option value="Music">Music</option>
                            <option value="Conference">Conference</option>
                            <option value="Sports">Sports</option>
                            <option value="Festival">Festival</option>
                            <option value="Workshop">Workshop</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Banner Image (Optional)</label>
                        <input type="file" name="banner_image" accept="image/*"
                               class="w-full px-5 py-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-8">
                <label class="block text-sm font-medium mb-2">Event Description</label>
                <textarea name="description" rows="6" required
                          class="w-full px-5 py-4 border border-gray-300 rounded-3xl focus:outline-none focus:border-indigo-500"
                          placeholder="Describe your event..."></textarea>
            </div>

            <button type="submit" 
                    class="mt-10 w-full bg-gradient-to-r from-indigo-600 to-violet-600 text-white py-5 rounded-3xl text-xl font-semibold hover:brightness-105 transition">
                Submit Event for Review
            </button>
        </form>
    </div>
</div>
@endsection