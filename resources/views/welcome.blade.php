<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $globalSettings['company_name'] ?? config('app.name', 'Billing System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .blob {
            filter: blur(40px);
            opacity: 0.15;
            z-index: -1;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-900 overflow-x-hidden">
    <!-- Blobs for Background -->
    <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-indigo-500 rounded-full blob -mr-48 -mt-48"></div>
    <div class="fixed bottom-0 left-0 w-[600px] h-[600px] bg-emerald-500 rounded-full blob -ml-64 -mb-64"></div>

    <div class="min-h-screen flex flex-col justify-between">
        <!-- Navbar -->
        <nav class="sticky top-0 z-50 py-6 px-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center glass rounded-2xl px-6 py-3 shadow-sm">
                <div class="flex items-center gap-3">
                    @if(!empty($globalSettings['app_logo']))
                        <img src="{{ asset('storage/' . $globalSettings['app_logo']) }}" alt="Logo" class="h-10 w-auto">
                    @endif
                    <span class="text-xl font-extrabold tracking-tight text-indigo-900">
                        {{ $globalSettings['company_name'] ?? 'Eliash' }}
                    </span>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="text-sm font-semibold text-gray-600 hover:text-indigo-600 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-semibold text-gray-600 hover:text-indigo-600 transition">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition duration-200 active:scale-95">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center px-4 py-20">
            <div class="max-w-4xl w-full text-center space-y-12">
                <div class="space-y-6">
                    <span
                        class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold uppercase tracking-widest border border-indigo-100">
                        Professional Billing & Customer Management
                    </span>
                    <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Sederhanakan Penagihan,<br>
                        <span class="text-indigo-600">Maksimalkan Bisnis Anda.</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed">
                        Kelola pelanggan, buat tagihan multi-item yang profesional, dan pantau pembayaran dengan sistem
                        otomatis yang terpercaya. Didesain khusus untuk efisiensi operasional skala UMKM hingga
                        korporasi.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="w-full md:w-auto px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition duration-200">
                            Masuk ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="w-full md:w-auto px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition duration-200">
                            Mulai Sekarang
                        </a>
                        <a href="{{ route('login') }}"
                            class="w-full md:w-auto px-8 py-4 bg-white text-gray-700 border border-gray-200 rounded-2xl font-bold hover:bg-gray-50 transition duration-200">
                            Coba Demo Gratis
                        </a>
                    @endauth
                </div>

                <div class="pt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 glass rounded-3xl text-left space-y-4 shadow-sm border-indigo-50">
                        <div
                            class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Tagihan Instan</h3>
                        <p class="text-sm text-gray-500">Buat invoice profesional dengan beberapa item sekaligus lengkap
                            dengan pajak otomatis.</p>
                    </div>
                    <div class="p-6 glass rounded-3xl text-left space-y-4 shadow-sm border-indigo-50">
                        <div
                            class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Pengingat Otomatis</h3>
                        <p class="text-sm text-gray-500">Kirim pengingat pembayaran via WhatsApp dan Email secara
                            otomatis sebelum jatuh tempo.</p>
                    </div>
                    <div class="p-6 glass rounded-3xl text-left space-y-4 shadow-sm border-indigo-50">
                        <div
                            class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Portal Customer</h3>
                        <p class="text-sm text-gray-500">Berikan akses portal mandiri bagi pelanggan untuk cek riwayat
                            layanan dan rincian transaksi.</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-10 border-t border-gray-100 px-4">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} {{ $globalSettings['company_name'] ?? 'Eliash' }}. All rights reserved.
                </div>
                <div class="flex items-center gap-6 text-gray-400 text-sm font-medium">
                    <a href="#" class="hover:text-indigo-600 transition">Terms</a>
                    <a href="#" class="hover:text-indigo-600 transition">Privacy</a>
                    <a href="#" class="hover:text-indigo-600 transition">Contact</a>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>