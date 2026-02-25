<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Services') }}
            </h2>
            <a href="{{ route('services.create') }}"
                class="px-4 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">
                + New Service
            </a>
        </div>
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
                    <h3 class="font-bold text-gray-700">All Services</h3>
                    <form method="GET" action="{{ route('services.index') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search service or customer..."
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
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="hover:text-gray-700 flex items-center">
                                        Service Name
                                        @if(request('sort') === 'name')
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
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => request('sort') === 'price' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="hover:text-gray-700 flex items-center">
                                        Price
                                        @if(request('sort') === 'price')
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
                                    Billing Cycle</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'next_due_date', 'direction' => request('sort') === 'next_due_date' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                        class="hover:text-gray-700 flex items-center">
                                        Next Due Date
                                        @if(request('sort') === 'next_due_date')
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
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($services as $service)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">{{ $service->name }}</td>
                                    <td class="px-6 py-4">{{ $service->customer?->name }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">Rp
                                        {{ number_format($service->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ ucfirst($service->billing_cycle->value ?? $service->billing_cycle) }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ $service->next_due_date?->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($service->status->value === 'active' || $service->status === 'active')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                        @elseif($service->status->value === 'suspended' || $service->status === 'suspended')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Suspended</span>
                                        @elseif($service->status->value === 'due' || $service->status === 'due')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Due</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($service->status->value ?? $service->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($service->status->value === 'active' || $service->status === 'active')
                                                <form method="POST"
                                                    action="{{ route('services.generate-invoice', $service->id) }}"
                                                    class="inline-block">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-900 bg-white border border-indigo-200 hover:bg-indigo-50 px-3 py-1.5 rounded-md transition duration-150">
                                                        Generate Invoice
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400 italic">No actions available</span>
                                            @endif

                                            <!-- Embed Widget Button -->
                                            <button type="button"
                                                onclick="prompt('Salin dan Tempel Kode JavaScript (Snippet) ini sebelum tag </body> di website pelanggan Anda:', '<script src=\'{{ route('widget.script', ['token' => $service->widget_token]) }}\' defer></script>')"
                                                class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-50 px-3 py-1.5 rounded-md transition duration-150 flex items-center gap-1"
                                                title="Ambil Kode Widget untuk Pelanggan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                                </svg>
                                                Widget
                                            </button>
                                            <a href="{{ route('widget.portal', ['token' => $service->widget_token]) }}"
                                                target="_blank"
                                                class="text-xs text-indigo-500 hover:text-indigo-700 underline"
                                                title="Preview Portal">Lihat Portal</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="px-6 py-4 border-t border-gray-100 bg-white">
                        {{ $services->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>