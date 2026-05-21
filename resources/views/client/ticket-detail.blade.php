@extends('layouts.app')

@section('title', 'Ticket Details')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 p-10 text-white">
            <h1 class="text-3xl font-bold">{{ $ticket->event->title }}</h1>
            <p class="mt-2">{{ $ticket->event->venue }}</p>
        </div>
        <div class="p-10">
            <div class="grid md:grid-cols-2 gap-10">
                <div>
                    <p class="text-sm text-gray-500">Ticket Code</p>
                    <p class="text-2xl font-mono font-bold text-indigo-600">{{ $ticket->ticket_code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Quantity</p>
                    <p class="text-3xl font-bold">{{ $ticket->quantity }} Tickets</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection