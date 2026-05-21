@extends('layouts.app')

@section('title', 'Request Refund')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold mb-8">Request Refund</h1>
    <div class="bg-white rounded-3xl shadow p-10">
        <form method="POST" action="{{ route('client.refund.store', $payment) }}">
            @csrf
            <textarea name="reason" rows="6" placeholder="Why do you want a refund?" 
                      class="w-full border rounded-2xl p-6" required></textarea>
            <button type="submit" class="mt-6 w-full bg-red-600 text-white py-4 rounded-2xl font-semibold">
                Submit Refund Request
            </button>
        </form>
    </div>
</div>
@endsection