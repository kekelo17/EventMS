@extends('layouts.app')

@section('title', 'Withdrawal Requests')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">Withdrawal Requests</h1>

    <div class="bg-white rounded-3xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-8 py-5 text-left">Organiser</th>
                    <th class="px-8 py-5 text-left">Amount</th>
                    <th class="px-8 py-5 text-left">Method</th>
                    <th class="px-8 py-5 text-left">Status</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($withdrawals as $withdrawal)
                <tr>
                    <td class="px-8 py-6">{{ $withdrawal->organiser->name }}</td>
                    <td class="px-8 py-6 font-semibold">{{ number_format($withdrawal->amount_requested, 2) }} XAF</td>
                    <td class="px-8 py-6 capitalize">{{ $withdrawal->payment_channel }}</td>
                    <td class="px-8 py-6">
                        @if($withdrawal->status === 'paid')
                            <span class="text-emerald-600">Paid</span>
                        @elseif($withdrawal->status === 'rejected')
                            <span class="text-red-600">Rejected</span>
                        @else
                            <span class="text-amber-600">Pending</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 text-center">
                        <a href="{{ route('admin.withdrawals.show', $withdrawal) ?? '#' }}" 
                           class="text-indigo-600 hover:underline">Review</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection