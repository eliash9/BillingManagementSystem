<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                {{ __('Customer Profile') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('customers.edit', $customer->id) }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Edit Profile
                </a>
                <a href="{{ route('services.create', ['customer_id' => $customer->id]) }}"
                    class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    + Add Service
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Overview Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-8 flex flex-col md:flex-row gap-8 items-start">
                    <div class="flex-shrink-0">
                        <div
                            class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-3xl font-bold border-4 border-white shadow-md">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                    </div>

                    <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h1>
                            <p class="text-indigo-600 font-medium text-sm mb-4">
                                {{ $customer->company ?? 'Individual Customer' }}</p>

                            <div class="space-y-2 mt-4">
                                <p class="text-sm flex items-center gap-2 text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <a href="mailto:{{ $customer->email }}"
                                        class="hover:text-indigo-600 hover:underline">{{ $customer->email }}</a>
                                </p>
                                @if($customer->phone)
                                    <p class="text-sm flex items-center gap-2 text-gray-600">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                        <a href="tel:{{ $customer->phone }}"
                                            class="hover:text-indigo-600 hover:underline">{{ $customer->phone }}</a>
                                    </p>
                                @endif
                                <p class="text-sm flex items-start gap-2 text-gray-600 mt-2">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $customer->address ?? 'No address provided' }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 h-fit">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Account
                                Metadata</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-xs font-medium text-gray-500">Customer ID</dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-900">
                                        #{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-xs font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        @php $status = $customer->status->value ?? $customer->status; @endphp
                                        @if($status === 'active')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
                                        @elseif($status === 'suspended')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Suspended</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-xs font-medium text-gray-500">Joined Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('M d, Y') }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-xs font-medium text-gray-500">Total Spent</dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-900">Rp
                                        {{ number_format($customer->invoices()->where('invoices.status', 'paid')->sum('amount'), 0, ',', '.') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Active Services List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="font-bold text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Subscribed Services
                        </h3>
                        <span
                            class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">{{ $customer->services->count() }}
                            Total</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($customer->services as $service)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">{{ $service->name }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ ucfirst($service->billing_cycle->value ?? $service->billing_cycle) }} • Rp
                                            {{ number_format($service->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        @if($service->status->value === 'active' || $service->status === 'active')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Active</span>
                                        @elseif($service->status->value === 'suspended' || $service->status === 'suspended')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Suspended</span>
                                        @elseif($service->status->value === 'due' || $service->status === 'due')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Due</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($service->status->value ?? $service->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-between items-center text-xs text-gray-500">
                                    <span>Next Due: <span
                                            class="font-medium {{ $service->next_due_date && $service->next_due_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">{{ $service->next_due_date ? $service->next_due_date->format('M d, Y') : 'N/A' }}</span></span>
                                    <!-- Optional Action if needed -->
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500 text-sm">
                                No services found.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Invoices List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="font-bold text-gray-700 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Recent Invoices
                        </h3>
                        <a href="{{ route('invoices.index', ['search' => $customer->name]) }}"
                            class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View All &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Invoice</th>
                                    <th class="px-6 py-3 font-medium">Amount</th>
                                    <th class="px-6 py-3 font-medium">Due</th>
                                    <th class="px-6 py-3 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($customer->invoices as $inv)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <a href="{{ route('invoices.show', $inv->id) }}"
                                                class="hover:text-indigo-600 hover:underline">{{ $inv->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4">Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $inv->due_date->format('M d') }}</td>
                                        <td class="px-6 py-4">
                                            @if($inv->status->value === 'paid' || $inv->status === 'paid')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">
                                            No invoices generated yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>