@extends('layouts.app')

@section('title', 'All Transactions')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-4xl font-bold mb-8">All Transactions</h1>

    <div class="bg-white rounded-3xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-8 py-5 text-left">Reference</th>
                    <th class="px-8 py-5 text-left">Type</th>
                    <th class="px-8 py-5 text-left">From</th>
                    <th class="px-8 py-5 text-left">To</th>
                    <th class="px-8 py-5 text-left">Amount</th>
                    <th class="px-8 py-5 text-left">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($transactions as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-8 py-6 font-mono text-sm">{{ $transaction->reference }}</td>
                    <td class="px-8 py-6 capitalize">{{ $transaction->type }}</td>
                    <td class="px-8 py-6">{{ $transaction->fromUser?->name ?? 'System' }}</td>
                    <td class="px-8 py-6">{{ $transaction->toUser?->name ?? 'System' }}</td>
                    <td class="px-8 py-6 font-semibold">{{ number_format($transaction->amount, 2) }} XAF</td>
                    <td class="px-8 py-6 text-gray-500">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</div>
@endsection