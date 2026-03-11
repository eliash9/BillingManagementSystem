<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-50 h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Billing Portal - {{ $customer->company ?? $customer->name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 min-h-full flex flex-col">

    <div class="flex-grow">
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span
                        class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">Billing
                        Portal</span>
                </div>
                <div class="text-sm text-gray-500 font-medium">Ditagihkan ke: <span
                        class="text-gray-900">{{ $customer->company ?? $customer->name }}</span></div>
            </div>
        </header>

        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            <!-- Customer Summary Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                <div class="p-8">
                    <h2 class="text-base font-semibold tracking-wide text-gray-500 uppercase mb-1">Detail Pelanggan</h2>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $customer->company ?? $customer->name }}</h3>
                    @if($customer->address)
                        <p class="mt-2 text-gray-600">{{ $customer->address }}</p>
                    @endif

                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold tracking-wide text-gray-500 uppercase mb-4">Layanan Anda</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($customer->services as $svc)
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <div class="flex justify-between items-start mb-2">
                                        <h5 class="font-bold text-gray-900">{{ $svc->name }}</h5>
                                        @if($svc->status->value === 'active' || $svc->status === 'active')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @elseif($svc->status->value === 'suspended' || $svc->status === 'suspended')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Ditangguhkan</span>
                                        @elseif($svc->status->value === 'due' || $svc->status === 'due')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">Jatuh
                                                Tempo</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($svc->status->value ?? $svc->status) }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm border-b border-gray-200 pb-2 mb-2">Rp
                                        {{ number_format($svc->price, 0, ',', '.') }} /
                                        {{ strtolower($svc->billing_cycle->value ?? $svc->billing_cycle) }}</p>
                                    <p class="text-xs text-gray-500">Perpanjangan:
                                        {{ $svc->next_due_date ? $svc->next_due_date->format('d M Y') : 'N/A' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invoices List -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                    Riwayat Tagihan
                </h3>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left whitespace-nowrap">
                            <thead
                                class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wider border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4">No Tagihan</th>
                                    <th class="px-6 py-4">Tgl Terbit</th>
                                    <th class="px-6 py-4">Tgl Jatuh Tempo</th>
                                    <th class="px-6 py-4 text-right">Jumlah</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse($customer->invoices as $inv)
                                    <tr
                                        class="hover:bg-gray-50 transition-colors {{ ($inv->status->value === 'unpaid' || $inv->status === 'unpaid') ? 'bg-orange-50/30' : '' }}">
                                        <td class="px-6 py-4 font-semibold text-gray-900">
                                            {{ $inv->invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $inv->issue_date->format('M d, Y') }}</td>
                                        <td
                                            class="px-6 py-4 font-medium {{ $inv->due_date->isPast() && ($inv->status->value === 'unpaid' || $inv->status === 'unpaid') ? 'text-red-600' : 'text-gray-600' }}">
                                            {{ $inv->due_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900">
                                            Rp {{ number_format($inv->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($inv->status->value === 'paid' || $inv->status === 'paid')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Lunas</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 ring-1 ring-inset ring-orange-500/20">Belum
                                                    Bayar</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('widget.invoice', ['token' => $customer->widget_token, 'invoice' => $inv->id]) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-md {{ ($inv->status->value === 'unpaid' || $inv->status === 'unpaid') ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 shadow-sm' }}">
                                                @if($inv->status->value === 'unpaid' || $inv->status === 'unpaid')
                                                    Bayar Sekarang
                                                @else
                                                    Lihat Kwitansi
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada tagihan untuk Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-sm text-gray-500">
            <div>Portal Penagihan Aman didukung oleh <span class="font-semibold text-gray-900">BillingSystem</span>
            </div>
            <div>&copy; {{ date('Y') }} Hak cipta dilindungi.</div>
        </div>
    </footer>
</body>

</html>