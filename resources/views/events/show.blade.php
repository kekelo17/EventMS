@extends('layouts.app')

@section('title', $event->title)

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">
    <div class="grid lg:grid-cols-5 gap-10">
        <!-- Left Side - Image & Info -->
        <div class="lg:col-span-3">
            @if($event->banner_image)
                <img src="{{ asset('storage/' . $event->banner_image) }}" 
                     class="w-full rounded-3xl shadow-xl">
            @else
                <div class="h-96 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl"></div>
            @endif

            <div class="mt-8">
                <h1 class="text-4xl font-bold mb-4">{{ $event->title }}</h1>
                <p class="text-xl text-gray-600">{{ $event->venue }}</p>
                <p class="text-lg mt-2">{{ $event->event_date->format('l, d F Y • H:i') }}</p>
            </div>

            <div class="prose mt-10">
                <h3 class="text-2xl font-semibold">About this Event</h3>
                <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
            </div>
        </div>

        <!-- Right Side - Booking Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl p-8 sticky top-8">
                <div class="text-5xl font-bold text-indigo-600 mb-2">
                    {{ number_format($event->price) }} <span class="text-2xl">XAF</span>
                </div>
                <p class="text-gray-500">per ticket</p>

                @if($event->isSoldOut())
                    <div class="mt-8 bg-red-100 text-red-700 p-6 rounded-2xl text-center font-medium">
                        Sorry, this event is sold out.
                    </div>
                @else
                    <a href="{{ route('client.reserve', $event) }}" 
                       class="mt-8 block w-full text-center py-5 bg-gradient-to-r from-indigo-600 to-violet-600 text-white text-xl font-semibold rounded-3xl hover:brightness-105 transition">
                        Reserve Tickets Now
                    </a>
                @endif

                <div class="mt-10 text-center text-sm text-gray-500">
                    <i class="fas fa-lock"></i> Secure payment with Escrow Protection
                </div>
            </div>
        </div>
    </div>
</div>
@endsection