<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-100 h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 min-h-full py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('widget.portal', $service->widget_token) }}"
                class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition">
                <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Portal
            </a>
            <button onclick="window.print()"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Cetak HTML
            </button>
        </div>

        <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg">
            <!-- Header -->
            <div class="px-8 py-10 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">TAGIHAN</h1>
                        <p class="mt-2 text-lg text-gray-500 font-medium">#{{ $invoice->invoice_number }}</p>
                    </div>
                    <div class="text-right">
                        @if($invoice->status->value === 'paid' || $invoice->status === 'paid')
                            <div
                                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-green-100 text-green-800 border border-green-200 shadow-sm uppercase tracking-wide">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Lunas
                            </div>
                        @else
                            <div
                                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm uppercase tracking-wide">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum Dibayar
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Addresses -->
                <div class="mt-10 grid grid-cols-2 gap-8 text-sm">
                    <div>
                        <h3 class="font-bold text-gray-900 uppercase tracking-wider mb-2 text-xs">Ditagihkan Kepada</h3>
                        <address class="not-italic text-gray-600 leading-relaxed">
                            <strong
                                class="text-gray-900 text-base">{{ $service->customer->company ?? $service->customer->name }}</strong><br>
                            @if($service->customer->company)
                                {{ $service->customer->name }}<br>
                            @endif
                            <a href="mailto:{{ $service->customer->email }}"
                                class="text-indigo-600 hover:text-indigo-900">{{ $service->customer->email }}</a><br>
                            {{ $service->customer->phone ?? '' }}<br>
                            {{ $service->customer->address ?? '' }}
                        </address>
                    </div>
                    <div class="text-right">
                        <h3 class="font-bold text-gray-900 uppercase tracking-wider mb-2 text-xs">Dari</h3>
                        <address class="not-italic text-gray-600 leading-relaxed">
                            <strong
                                class="text-gray-900 text-base">{{ $settings['company_name'] ?? 'Eliash' }}</strong><br>
                            {{ $settings['company_email'] ?? 'halo@eliash.my.id' }}<br>
                            {{ $settings['company_phone'] ?? '+62 85546774992' }}<br>
                            {!! nl2br(e($settings['company_address'] ?? 'Kav Kampungbaru Ngempit Kraton Pasuruan')) !!}
                        </address>
                    </div>
                </div>

                <!-- Dates & Amounts -->
                <div class="mt-10 pt-8 border-t border-gray-100 grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="block font-medium text-gray-500 uppercase text-xs mb-1">Tgl Terbit</span>
                        <strong class="text-gray-900">{{ $invoice->issue_date->format('M d, Y') }}</strong>
                    </div>
                    <div>
                        <span class="block font-medium text-gray-500 uppercase text-xs mb-1">Jatuh Tempo</span>
                        <strong
                            class="{{ $invoice->due_date->isPast() && ($invoice->status->value === 'unpaid' || $invoice->status === 'unpaid') ? 'text-red-600' : 'text-gray-900' }}">{{ $invoice->due_date->format('M d, Y') }}</strong>
                    </div>
                    <div class="text-right">
                        <span class="block font-medium text-gray-500 uppercase text-xs mb-1">Jumlah Tagihan</span>
                        <strong class="text-xl text-indigo-700">Rp
                            {{ number_format($invoice->amount, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Summary Table -->
            <div class="px-8 py-10 bg-gray-50/50">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="py-3 font-semibold text-gray-900">Deskripsi Layanan</th>
                            <th class="py-3 font-semibold text-gray-900 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-5 text-gray-700 font-medium">
                                {{ $service->name }} <span
                                    class="text-gray-500 font-normal">({{ ucfirst($service->billing_cycle->value ?? $service->billing_cycle) }})</span>
                            </td>
                            <td class="py-5 text-right font-medium text-gray-900">Rp
                                {{ number_format($invoice->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200">
                            <td class="pt-6 font-bold text-gray-900 text-right uppercase tracking-wider text-xs">Total
                                Tagihan</td>
                            <td class="pt-6 font-bold text-gray-900 text-right text-lg">Rp
                                {{ number_format($invoice->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($invoice->status->value === 'unpaid' || $invoice->status === 'unpaid')
                <!-- Payment Instructions -->
                <div class="px-8 py-10 border-t border-gray-200 bg-white">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Instruksi Pembayaran</h3>
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-5">
                        <p class="text-sm text-indigo-900 mb-4">Silakan transfer dengan jumlah yang tepat ke rekening bank
                            berikut. Cantumkan nomor tagihan <strong>#{{ $invoice->invoice_number }}</strong> sebagai
                            berita / referensi transaksi.</p>

                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2 text-sm">
                            <div class="sm:col-span-1">
                                <dt class="font-medium text-indigo-800">Nama Bank</dt>
                                <dd class="mt-1 font-bold text-indigo-900">
                                    {{ $settings['bank_name'] ?? 'Bank Central Asia (BCA)' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="font-medium text-indigo-800">Nomor Rekening</dt>
                                <dd class="mt-1 font-bold text-indigo-900 text-base">
                                    {{ $settings['bank_account_number'] ?? '123 456 7890' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="font-medium text-indigo-800">Atas Nama</dt>
                                <dd class="mt-1 font-bold text-indigo-900">
                                    {{ $settings['bank_account_holder'] ?? 'PT SaaS Company Indonesia' }}</dd>
                            </div>
                        </dl>

                        <div class="mt-6 pt-6 border-t border-indigo-200 text-center">
                            <p class="text-sm text-indigo-800 mb-3">Setelah melakukan pembayaran, mohon konfirmasi kepada
                                tim kami
                                melalui WhatsApp atau Email.</p>
                            <a href="#"
                                class="inline-flex justify-center items-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Konfirmasi Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <p class="mt-8 text-center text-sm text-gray-500">
            Terima kasih telah berlangganan layanan kami.
        </p>
    </div>

</body>

</html>