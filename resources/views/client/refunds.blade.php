@extends('layouts.app')

@section('title', 'My Refund Requests')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold">Refund Requests</h1>
        <a href="{{ route('client.tickets') }}" 
           class="text-indigo-600 hover:underline flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Back to My Tickets
        </a>
    </div>

    @if($refunds->isEmpty())
        <div class="bg-white rounded-3xl shadow p-16 text-center">
            <div class="text-6xl mb-6">📭</div>
            <h3 class="text-2xl font-semibold text-gray-700 mb-3">No Refund Requests Yet</h3>
            <p class="text-gray-500 max-w-md mx-auto">
                You haven't submitted any refund requests. All your tickets are safe and active.
            </p>
        </div>
    @else
        <div class="bg-white rounded-3xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-8 py-5 text-left font-medium text-gray-600">Event</th>
                        <th class="px-8 py-5 text-left font-medium text-gray-600">Amount Requested</th>
                        <th class="px-8 py-5 text-left font-medium text-gray-600">Status</th>
                        <th class="px-8 py-5 text-left font-medium text-gray-600">Requested On</th>
                        <th class="px-8 py-5 text-center font-medium text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($refunds as $refund)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-8 py-6">
                            <div class="font-medium">{{ $refund->payment->ticket->event->title }}</div>
                            <div class="text-sm text-gray-500">{{ $refund->payment->ticket->event->venue }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="font-semibold text-lg">
                                {{ number_format($refund->amount_requested, 2) }} XAF
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            @if($refund->status === 'approved')
                                <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-700">
                                    <i class="fas fa-check mr-1"></i> Approved
                                </span>
                            @elseif($refund->status === 'rejected')
                                <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                    <i class="fas fa-times mr-1"></i> Rejected
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-700">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-gray-500">
                            {{ $refund->created_at->format('d M Y') }}
                        </td>
                        <td class="px-8 py-6 text-center">
                            <a href="#" 
                               class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                View Details →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection