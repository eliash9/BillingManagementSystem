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
                            <p class="text-lg font-bold text-gray-900">{{ $invoice->service?->customer?->name }}</p>
                            <p class="text-sm text-gray-600 mt-1">Customer ID:
                                #{{ str_pad($invoice->service?->customer?->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1 tracking-wider uppercase font-semibold">Service Info
                            </p>
                            <p class="text-base font-semibold text-gray-900">{{ $invoice->service?->name }}</p>
                            <p class="text-sm text-gray-600 mt-1 capitalize">Cycle:
                                {{ $invoice->service?->billing_cycle->value ?? $invoice->service?->billing_cycle }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-4 px-6 bg-gray-50 rounded-lg">
                        <span class="text-gray-700 font-medium">Total Amount Due</span>
                        <span class="text-2xl font-bold text-indigo-700">Rp
                            {{ number_format($invoice->amount, 0, ',', '.') }}</span>
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
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($invoice->payments as $payment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-6 text-sm text-gray-700">
                                        {{ $payment->created_at->format('M d, Y H:i') }}</td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-900">Rp
                                        {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td class="py-4 px-6">
                                        @if($payment->status->value === 'verified' || $payment->status === 'verified')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($payment->status->value ?? $payment->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-500">{{ $payment->verifier?->name ?? 'System' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 px-6 text-center text-gray-400">
                                        No payment logs found for this invoice.
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