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
            <a href="{{ route('widget.portal', $customer->widget_token) }}"
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

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white shadow-lg overflow-hidden sm:rounded-lg">
            <!-- Header -->
            <div class="px-8 py-10 border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        @if(!empty($globalSettings['app_logo']))
                            <img class="h-16 w-auto mb-4" src="{{ asset('storage/' . $globalSettings['app_logo']) }}"
                                alt="Logo">
                        @endif
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
                        @elseif($invoice->payments->where('status', 'pending')->first())
                            <div
                                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-blue-100 text-blue-800 border border-blue-200 shadow-sm uppercase tracking-wide">
                                <svg class="w-4 h-4 mr-1.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-2M10 11l2 2 4-4"></path>
                                </svg>
                                Sedang Diverif
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
                                class="text-gray-900 text-base">{{ $customer->company ?? $customer->name }}</strong><br>
                            @if($customer->company)
                                {{ $customer->name }}<br>
                            @endif
                            <a href="mailto:{{ $customer->email ?? '' }}"
                                class="text-indigo-600 hover:text-indigo-900">{{ $customer->email ?? '' }}</a><br>
                            {{ $customer->phone ?? '' }}<br>
                            {{ $customer->address ?? '' }}
                        </address>
                    </div>
                    <div class="text-right">
                        <h3 class="font-bold text-gray-900 uppercase tracking-wider mb-2 text-xs">Dari</h3>
                        <address class="not-italic text-gray-600 leading-relaxed">
                            <strong
                                class="text-gray-900 text-base">{{ $globalSettings['company_name'] ?? 'Eliash' }}</strong><br>
                            {{ $globalSettings['company_email'] ?? 'halo@eliash.my.id' }}<br>
                            {{ $globalSettings['company_phone'] ?? '+62 85546774992' }}<br>
                            {!! nl2br(e($globalSettings['company_address'] ?? 'Kav Kampungbaru Ngempit Kraton Pasuruan')) !!}
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
                <table class="w-full text-left text-sm mb-4">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="py-3 font-semibold text-gray-900 w-1/2">Deskripsi Layanan</th>
                            <th class="py-3 font-semibold text-gray-900 text-center w-1/6">Qty</th>
                            <th class="py-3 font-semibold text-gray-900 text-right w-1/6">Harga Satuan</th>
                            <th class="py-3 font-semibold text-gray-900 text-right w-1/6">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($invoice->items as $item)
                            <tr>
                                <td class="py-4 text-gray-700 font-medium">
                                    {{ $item->description }}
                                </td>
                                <td class="py-4 text-center text-gray-900">
                                    {{ rtrim(rtrim(number_format($item->quantity, 2, ',', '.'), '0'), ',') }}
                                </td>
                                <td class="py-4 text-right text-gray-900">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 text-right font-medium text-gray-900">
                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500 italic">Data item tidak tersedia.</td>
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
                            <span>Pajak ({{ rtrim(rtrim($invoice->tax_rate, '0'), '.') }}%)</span>
                            <span class="font-medium text-gray-900">Rp
                                {{ number_format($invoice->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        <div
                            class="flex justify-between py-4 text-base font-bold text-gray-900 border-t border-gray-200 mt-2">
                            <span class="uppercase tracking-wider">Total Tagihan</span>
                            <span class="text-indigo-700 text-lg">Rp
                                {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($invoice->status->value === 'unpaid' || $invoice->status === 'unpaid')
                @php
                    $paymentMethods = isset($globalSettings['payment_methods']) ? json_decode($globalSettings['payment_methods'], true) : [];
                @endphp
                <!-- Payment Instructions -->
                <div class="px-8 py-10 border-t border-gray-200 bg-white" x-data="{ showConfirmForm: false }">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Instruksi Pembayaran</h3>
                    
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-5">
                        <p class="text-sm text-indigo-900 mb-6">Silakan transfer dengan jumlah yang tepat ke salah satu rekening/e-wallet berikut. Cantumkan nomor tagihan <strong>#{{ $invoice->invoice_number }}</strong> sebagai berita / referensi transaksi.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($paymentMethods as $method)
                                <div class="bg-white border border-indigo-100 rounded-lg p-4 shadow-sm relative overflow-hidden group">
                                    <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition">
                                        @if($method['type'] === 'bank')
                                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M3 10h18v10.004c0 .55-.445.996-.996.996H3.996A.997.997 0 013 20.004V10zm2 2v6h14v-6H5zm8 2h4v2h-4v-2zM3 4c0-.552.448-1 1-1h16c.552 0 1 .448 1 1v4H3V4zm2 2v2h14V6H5z"/></svg>
                                        @else
                                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7V5c0-1.1-.9-2-2-2H5.01c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2H18c1.1 0 2-.9 2-2v-2h2V7h-2zM5.01 5H18v2H5.01V5zM18 19H5.01V9H18v10zM21 15h-4v-2h4v2z"/></svg>
                                        @endif
                                    </div>
                                    <dt class="text-xs font-semibold text-indigo-500 uppercase tracking-wider mb-1">{{ ($method['type'] === 'bank' ? 'BANK' : 'E-WALLET') . ' ' . $method['name'] }}</dt>
                                    <dd class="text-lg font-bold text-gray-900 mb-1 leading-none select-all">{{ $method['account_number'] }}</dd>
                                    <dd class="text-sm text-gray-600 font-medium italic">a.n. {{ $method['account_holder'] }}</dd>
                                </div>
                            @empty
                                <div class="col-span-2">
                                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2 text-sm">
                                        <div class="sm:col-span-1">
                                            <dt class="font-medium text-indigo-800">Nama Bank</dt>
                                            <dd class="mt-1 font-bold text-indigo-900">
                                                {{ $globalSettings['bank_name'] ?? 'Bank Central Asia (BCA)' }}
                                            </dd>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <dt class="font-medium text-indigo-800">Nomor Rekening</dt>
                                            <dd class="mt-1 font-bold text-indigo-900 text-base">
                                                {{ $globalSettings['bank_account_number'] ?? '123 456 7890' }}
                                            </dd>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <dt class="font-medium text-indigo-800">Atas Nama</dt>
                                            <dd class="mt-1 font-bold text-indigo-900">
                                                {{ $globalSettings['bank_account_holder'] ?? 'PT SaaS Company Indonesia' }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-8 pt-8 border-t border-indigo-200">
                            <!-- Confirmation Section -->
                            @if($invoice->payments->where('status', 'pending')->count() > 0)
                                <div class="text-center bg-blue-50 border border-blue-100 rounded-lg p-4">
                                    <p class="text-sm text-blue-800 font-medium">Anda telah mengirimkan konfirmasi pembayaran. Mohon tunggu verifikasi dari tim kami.</p>
                                    @if($invoice->payments->where('status', 'pending')->first()->proof_path)
                                        <p class="mt-2 text-xs text-blue-600 italic">Bukti transfer telah kami terima.</p>
                                    @endif
                                </div>
                            @else
                                <div x-show="!showConfirmForm" class="text-center">
                                    <p class="text-sm text-indigo-800 mb-4">Sudah melakukan pembayaran? Lampirkan bukti transfer untuk mempercepat proses verifikasi.</p>
                                    <button type="button" @click="showConfirmForm = true"
                                        class="inline-flex justify-center items-center py-2.5 px-8 border border-transparent shadow-sm text-sm font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                        Konfirmasi Sekarang
                                    </button>
                                </div>

                                <div x-show="showConfirmForm" x-transition 
                                    class="bg-white border border-indigo-200 rounded-xl p-6 shadow-inner">
                                    <form action="{{ route('widget.invoice.confirm', [$customer->widget_token, $invoice->id]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="font-bold text-indigo-900">Form Konfirmasi Pembayaran</h4>
                                            <button type="button" @click="showConfirmForm = false" class="text-gray-400 hover:text-gray-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>

                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                                                <select name="payment_method" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                                    <option value="">-- Pilih Rekening Tujuan --</option>
                                                    @foreach($paymentMethods as $method)
                                                        <option value="{{ $method['type'] . ' ' . $method['name'] }}">{{ $method['name'] }} ({{ $method['account_number'] }})</option>
                                                    @endforeach
                                                    @if(empty($paymentMethods))
                                                        <option value="{{ $globalSettings['bank_name'] ?? 'Bank' }}">{{ $globalSettings['bank_name'] ?? 'Bank' }}</option>
                                                    @endif
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Transfer (Gambar)</label>
                                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition cursor-pointer relative" 
                                                     x-data="{ fileName: '' }">
                                                    <div class="space-y-1 text-center">
                                                        <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="flex text-sm text-gray-600">
                                                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                                                <span>Upload file</span>
                                                                <input name="proof" type="file" required class="sr-only" @change="fileName = $event.target.files[0].name" accept="image/*">
                                                            </label>
                                                            <p class="pl-1">atau drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500" x-text="fileName || 'PNG, JPG up to 5MB'"></p>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="submit" class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                                Kirim Konfirmasi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
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