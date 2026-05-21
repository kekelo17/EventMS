@extends('layouts.app')

@section('title', 'Manage Events')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">All Events</h1>

    <div class="bg-white rounded-3xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-8 py-5 text-left">Event</th>
                    <th class="px-8 py-5 text-left">Organiser</th>
                    <th class="px-8 py-5 text-left">Date</th>
                    <th class="px-8 py-5 text-left">Status</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($events as $event)
                <tr class="hover:bg-gray-50">
                    <td class="px-8 py-6">
                        <div class="font-medium">{{ $event->title }}</div>
                        <div class="text-sm text-gray-500">{{ $event->venue }}</div>
                    </td>
                    <td class="px-8 py-6">{{ $event->organiser->name }}</td>
                    <td class="px-8 py-6">{{ $event->event_date->format('d M Y') }}</td>
                    <td class="px-8 py-6">
                        @if($event->status === 'approved')
                            <span class="bg-emerald-100 text-emerald-700 px-4 py-1 rounded-full text-sm">Approved</span>
                        @elseif($event->status === 'pending')
                            <span class="bg-amber-100 text-amber-700 px-4 py-1 rounded-full text-sm">Pending</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-4 py-1 rounded-full text-sm">Rejected</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-center">
                        <a href="{{ route('admin.events.show', $event) }}" class="text-indigo-600 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection