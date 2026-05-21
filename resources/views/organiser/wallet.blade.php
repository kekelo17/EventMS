@extends('layouts.app')

@section('title', 'My Wallet')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">Escrow Wallet</h1>

    <div class="grid md:grid-cols-3 gap-8">
        <!-- Balance Cards -->
        <div class="bg-white rounded-3xl p-8 shadow">
            <p class="text-gray-500">Held in Escrow</p>
            <p class="text-5xl font-bold text-amber-600 mt-4">
                {{ number_format($wallet->held_balance ?? 0, 2) }} <span class="text-2xl">XAF</span>
            </p>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow">
            <p class="text-gray-500">Available Balance</p>
            <p class="text-5xl font-bold text-emerald-600 mt-4">
                {{ number_format($wallet->available_balance ?? 0, 2) }} <span class="text-2xl">XAF</span>
            </p>
        </div>

        <div class="bg-white rounded-3xl p-8 shadow">
            <p class="text-gray-500">Total Withdrawn</p>
            <p class="text-5xl font-bold text-gray-700 mt-4">
                {{ number_format($wallet->total_withdrawn ?? 0, 2) }} <span class="text-2xl">XAF</span>
            </p>
        </div>
    </div>

    <div class="mt-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Recent Withdrawals</h2>
            <a href="{{ route('organiser.withdraw.create') }}" 
               class="px-8 py-4 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition">
                Request Withdrawal
            </a>
        </div>

        @if($recent_withdrawals->isEmpty())
            <p class="text-gray-500">No withdrawal requests yet.</p>
        @else
            <!-- Table of recent withdrawals -->
            <div class="bg-white rounded-3xl shadow overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-8 py-5 text-left">Amount</th>
                            <th class="px-8 py-5 text-left">Channel</th>
                            <th class="px-8 py-5 text-left">Status</th>
                            <th class="px-8 py-5 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($recent_withdrawals as $withdrawal)
                        <tr>
                            <td class="px-8 py-6 font-semibold">{{ number_format($withdrawal->amount_requested, 2) }} XAF</td>
                            <td class="px-8 py-6">{{ ucfirst($withdrawal->payment_channel) }}</td>
                            <td class="px-8 py-6">
                                @if($withdrawal->status === 'paid')
                                    <span class="text-emerald-600">✓ Paid</span>
                                @elseif($withdrawal->status === 'rejected')
                                    <span class="text-red-600">Rejected</span>
                                @else
                                    <span class="text-amber-600">Pending</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-gray-500">{{ $withdrawal->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection