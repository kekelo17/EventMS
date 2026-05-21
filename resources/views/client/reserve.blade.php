@extends('layouts.app')

@section('title', 'Reserve Tickets')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold mb-8">Reserve Tickets - {{ $event->title }}</h1>

    <div class="bg-white rounded-3xl shadow p-8">
        <form method="POST" action="{{ route('client.reserve', $event) }}">
            @csrf
            <div class="mb-8">
                <label class="block text-sm font-medium mb-3">How many tickets?</label>
                <input type="number" name="quantity" min="1" max="10" value="1" 
                       class="w-full text-4xl text-center py-6 border-2 border-dashed rounded-3xl focus:outline-none focus:border-indigo-500">
            </div>

            <button type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-5 rounded-3xl text-xl font-semibold">
                Reserve & Proceed to Payment
            </button>
        </form>
    </div>
</div>
@endsection