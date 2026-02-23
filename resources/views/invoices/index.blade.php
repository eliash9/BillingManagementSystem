<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-700">All Invoices</h3>
                    <form method="GET" action="{{ route('invoices.index') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search invoice or customer..."
                            class="border-gray-300 rounded-l-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm w-64">
                        <button type="submit"
                            class="bg-indigo-600 border border-transparent rounded-r-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Search
                        </button>
                    </form>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full whitespace-nowrap divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'invoice_number', 'direction' => request('sort') === 'invoice_number' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="hover:text-gray-700 flex items-center">
                                        Invoice #
                                        @if(request('sort') === 'invoice_number')
                                            <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ request('direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                                </path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'amount', 'direction' => request('sort') === 'amount' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="hover:text-gray-700 flex items-center">
                                        Amount
                                        @if(request('sort') === 'amount')
                                            <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ request('direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                                </path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Issue Date</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'due_date', 'direction' => request('sort') === 'due_date' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="hover:text-gray-700 flex items-center">
                                        Due Date
                                        @if(request('sort') === 'due_date')
                                            <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ request('direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                                </path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="hover:text-gray-700 flex items-center">
                                        Status
                                        @if(request('sort') === 'status')
                                            <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ request('direction') === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                                </path>
                                            </svg>
                                        @endif
                                    </a>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($invoices as $invoice)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->service?->customer?->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp
                                        {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->issue_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $invoice->due_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($invoice->status->value === 'paid' || $invoice->status === 'paid')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                                        @elseif($invoice->status->value === 'unpaid' || $invoice->status === 'unpaid')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Unpaid</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($invoice->status->value ?? $invoice->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('invoices.show', $invoice->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 border border-indigo-200 hover:bg-indigo-50 px-3 py-1.5 rounded-md transition duration-150">Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="px-6 py-4 border-t border-gray-100 bg-white">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>