@extends('layouts.app')

@section('title', 'Request Withdrawal')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-4xl font-bold mb-2">Request Withdrawal</h1>
    <p class="text-gray-600 mb-10">Withdraw funds from your available balance</p>

    <div class="bg-white rounded-3xl shadow-xl p-10">
        <div class="mb-8 p-6 bg-emerald-50 border border-emerald-200 rounded-2xl">
            <p class="text-emerald-700 font-medium">Available Balance</p>
            <p class="text-4xl font-bold text-emerald-600">
                {{ number_format($wallet->available_balance, 2) }} XAF
            </p>
        </div>

        <form method="POST" action="{{ route('organiser.withdraw.store') }}">
            @csrf

            <div class="space-y-8">
                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium mb-3">Withdrawal Amount (XAF)</label>
                    <input type="number" 
                           name="amount" 
                           min="100" 
                           max="{{ $wallet->available_balance }}" 
                           step="100"
                           placeholder="Minimum 100 XAF"
                           class="w-full px-6 py-5 text-3xl border border-gray-300 rounded-3xl focus:outline-none focus:border-indigo-500 text-center font-semibold"
                           required>
                    <p class="text-xs text-gray-500 mt-2">Minimum withdrawal: 100 XAF</p>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium mb-3">Payment Method</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_channel" value="mobile_money" class="peer hidden" checked>
                            <div class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50 border-2 border-gray-200 rounded-2xl p-6 text-center">
                                <i class="fas fa-mobile-alt text-3xl mb-3 text-indigo-600"></i>
                                <p class="font-medium">Mobile Money</p>
                            </div>
                        </label>

                        <label class="cursor-pointer">
                            <input type="radio" name="payment_channel" value="bank" class="peer hidden">
                            <div class="peer-checked:border-indigo-600 peer-checked:bg-indigo-50 border-2 border-gray-200 rounded-2xl p-6 text-center">
                                <i class="fas fa-bank text-3xl mb-3 text-indigo-600"></i>
                                <p class="font-medium">Bank Transfer</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Mobile Money Fields -->
                <div id="mobile-fields">
                    <label class="block text-sm font-medium mb-2">Mobile Money Number</label>
                    <input type="text" name="mobile_money_number" 
                           placeholder="e.g. 6XX XXX XXX"
                           class="w-full px-6 py-4 border border-gray-300 rounded-2xl">
                </div>

                <!-- Bank Fields (Hidden by default) -->
                <div id="bank-fields" class="hidden">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Bank Name</label>
                            <input type="text" name="bank_name" class="w-full px-6 py-4 border border-gray-300 rounded-2xl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Account Number</label>
                            <input type="text" name="account_number" class="w-full px-6 py-4 border border-gray-300 rounded-2xl">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-2">Account Name</label>
                            <input type="text" name="account_name" class="w-full px-6 py-4 border border-gray-300 rounded-2xl">
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white py-5 rounded-3xl text-xl font-semibold hover:brightness-105 transition">
                    Submit Withdrawal Request
                </button>
            </div>
        </form>
    </div>

    <p class="text-center text-gray-500 mt-8 text-sm">
        Withdrawals are reviewed by admin and usually processed within 48 hours.
    </p>
</div>

<script>
document.querySelectorAll('input[name="payment_channel"]').forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.value === 'mobile_money') {
            document.getElementById('mobile-fields').classList.remove('hidden');
            document.getElementById('bank-fields').classList.add('hidden');
        } else {
            document.getElementById('mobile-fields').classList.add('hidden');
            document.getElementById('bank-fields').classList.remove('hidden');
        }
    });
});
</script>
@endsection