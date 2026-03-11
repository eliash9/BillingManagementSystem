<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <a href="{{ route('invoices.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                {{ __('Invoice') }} <span class="text-indigo-600 font-bold">#{{ $invoice->invoice_number }}</span>
            </h2>

            @if(in_array($invoice->status->value ?? $invoice->status, ['paid']))
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <svg class="mr-1.5 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    Paid
                </span>
            @else
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    <svg class="mr-1.5 h-4 w-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Unpaid
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Paper-like Invoice Card -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-gray-100 relative">
                <!-- Top Decorator Line -->
                <div
                    class="absolute top-0 left-0 w-full h-2 {{ in_array($invoice->status->value ?? $invoice->status, ['paid']) ? 'bg-green-500' : 'bg-indigo-600' }}">
                </div>

                <div class="p-8 sm:p-12">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            @if(!empty($globalSettings['app_logo']))
                                <img class="h-16 w-auto mb-4" src="{{ asset('storage/' . $globalSettings['app_logo']) }}"
                                    alt="Logo">
                            @endif
                            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">INVOICE</h1>
                            <p class="text-sm text-gray-500 mt-1">Ref: <span
                                    class="font-medium text-gray-700">{{ $invoice->invoice_number }}</span></p>
                        </div>
                        <div class="text-right">
                            <h2 class="text-lg font-bold text-gray-700">{{ config('app.name', 'Billing System') }}</h2>
                            <p class="text-sm text-gray-500">Issued: {{ $invoice->issue_date->format('M d, Y') }}</p>
                            <p
                                class="text-sm text-gray-500 font-medium {{ $invoice->due_date->isPast() && in_array($invoice->status->value ?? $invoice->status, ['unpaid']) ? 'text-red-600' : '' }}">
                                Due: {{ $invoice->due_date->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8 mb-10 pb-8 border-b border-gray-100">
                        <div>
                            <p class="text-sm text-gray-500 mb-1 tracking-wider uppercase font-semibold">Billed To</p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ $invoice->customer?->company ?? $invoice->customer?->name }}</p>
                            <p class="text-sm text-gray-600 mt-1">Customer ID:
                                #{{ str_pad($invoice->customer?->id ?? 0, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1 tracking-wider uppercase font-semibold">Invoice Details
                            </p>
                            <p class="text-base font-semibold text-gray-900">Total Items: {{ $invoice->items->count() }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">Status:
                                {{ ucfirst($invoice->status->value ?? $invoice->status) }}</p>
                        </div>
                    </div>

                    <div class="mb-8 bg-gray-50/50 rounded-lg border border-gray-100 p-6">
                        <table class="w-full text-left text-sm mb-4">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="py-3 font-semibold text-gray-900 w-1/2">Item Description</th>
                                    <th class="py-3 font-semibold text-gray-900 text-center w-1/6">Qty</th>
                                    <th class="py-3 font-semibold text-gray-900 text-right w-1/6">Unit Price</th>
                                    <th class="py-3 font-semibold text-gray-900 text-right w-1/6">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($invoice->items as $item)
                                    <tr>
                                        <td class="py-3 text-gray-700 font-medium">
                                            {{ $item->description }}
                                        </td>
                                        <td class="py-3 text-center text-gray-900">
                                            {{ rtrim(rtrim(number_format($item->quantity, 2, ',', '.'), '0'), ',') }}
                                        </td>
                                        <td class="py-3 text-right text-gray-900">
                                            Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 text-right font-medium text-gray-900">
                                            Rp {{ number_format($item->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500 italic">No items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="flex justify-end pt-4 border-t-2 border-gray-200">
                            <div class="w-1/2 md:w-1/3">
                                <div class="flex justify-between py-2 text-sm text-gray-600">
                                    <span>Sub Total</span>
                                    <span class="font-medium text-gray-900">Rp
                                        {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-2 text-sm text-gray-600">
                                    <span>Tax ({{ rtrim(rtrim($invoice->tax_rate, '0'), '.') }}%)</span>
                                    <span class="font-medium text-gray-900">Rp
                                        {{ number_format($invoice->tax_amount, 0, ',', '.') }}</span>
                                </div>
                                <div
                                    class="flex justify-between py-4 text-base font-bold text-gray-900 border-t border-gray-200 mt-2">
                                    <span class="uppercase tracking-wider">Total Amount Due</span>
                                    <span class="text-indigo-700 text-lg">Rp
                                        {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Payment Recording Block -->
            @if(in_array($invoice->status->value ?? $invoice->status, ['unpaid']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center gap-2 mb-6 text-indigo-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <h3 class="font-bold text-lg">Record Manual Payment</h3>
                        </div>

                        @if ($errors->any())
                            <div class="mb-5 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm"
                                role="alert">
                                <ul class="list-disc list-inside text-sm font-medium">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('invoices.pay', $invoice->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="amount"
                                        class="block font-semibold text-xs text-gray-600 uppercase tracking-wider mb-2">Amount
                                        Paid (IDR)</label>
                                    <input id="amount" type="number" name="amount"
                                        value="{{ old('amount', floatval($invoice->amount)) }}"
                                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                                        required min="1">
                                </div>
                                <div>
                                    <label for="payment_method"
                                        class="block font-semibold text-xs text-gray-600 uppercase tracking-wider mb-2">Payment
                                        Method</label>
                                    <select id="payment_method" name="payment_method"
                                        class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                                        required>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cash">Cash</option>
                                        <option value="e_wallet">E-Wallet</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="proof_path"
                                        class="block font-semibold text-xs text-gray-600 uppercase tracking-wider mb-2">Payment
                                        Proof</label>
                                    <!-- Custom styling for file input using Tailwind file modifiers -->
                                    <input id="proof_path" type="file" name="proof_path"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition duration-150 cursor-pointer"
                                        accept="image/jpeg,image/png,application/pdf">
                                </div>
                            </div>
                            <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Payment History Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="font-bold text-lg text-gray-800">Payment History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date
                                </th>
                                <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Method</th>
                                <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Verified By</th>
                                <th class="py-3 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($invoice->payments as $payment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 text-sm text-gray-700">
                                        {{ $payment->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900">Rp
                                        {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($payment->status->value === 'verified' || $payment->status === 'verified')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>
                                        @elseif($payment->status->value === 'failed' || $payment->status === 'failed')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ ucfirst($payment->status->value ?? $payment->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500 text-center">
                                        {{ $payment->verifier?->name ?? '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-sm">
                                        <div class="flex items-center gap-2">
                                            @if($payment->proof_path)
                                                <a href="{{ asset('storage/' . $payment->proof_path) }}" target="_blank"
                                                    class="inline-flex items-center px-2 py-1 bg-white border border-gray-300 rounded text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                                                    Lihat Bukti
                                                </a>
                                            @endif

                                            @if($payment->status->value === 'pending' || $payment->status === 'pending')
                                                <form action="{{ route('invoices.payments.verify', [$invoice->id, $payment->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                        class="inline-flex items-center px-2 py-1 bg-emerald-600 border border-transparent rounded text-xs font-semibold text-white hover:bg-emerald-700 transition shadow-sm"
                                                        onclick="return confirm('Verifikasi pembayaran ini?')">
                                                        Verif
                                                    </button>
                                                </form>
                                                <form action="{{ route('invoices.payments.reject', [$invoice->id, $payment->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                        class="inline-flex items-center px-2 py-1 bg-white border border-red-300 rounded text-xs font-semibold text-red-600 hover:bg-red-50 transition shadow-sm"
                                                        onclick="return confirm('Tolak pembayaran ini?')">
                                                        Tolak
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 px-6 text-center text-gray-400">
                                        Belum ada history pembayaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>