<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Professional Welcome Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Business Analytics</h1>
                    <p class="mt-1 text-sm text-gray-500 font-medium italic">Data real-time untuk analisa kesehatan bisnis Anda.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download Report
                    </button>
                </div>
            </div>

            <!-- Enhanced Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- MRR Card -->
                <div class="bg-indigo-600 rounded-2xl p-6 shadow-xl border border-indigo-700 text-white relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs font-bold text-indigo-200 uppercase tracking-widest">Est. Monthly Recurring Revenue</p>
                    <p class="mt-2 text-3xl font-extrabold">Rp {{ number_format($mrr, 0, ',', '.') }}</p>
                    <div class="mt-4 flex items-center text-xs text-indigo-100 italic">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Aktual dari {{ $activeServices }} layanan aktif
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow relative">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Collected Revenue</p>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <div class="mt-4 flex items-center text-xs text-emerald-600 font-bold">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Dana terverifikasi sistem
                    </div>
                </div>

                <!-- Outstanding Card -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Outstanding (Unpaid)</p>
                    <p class="mt-2 text-3xl font-extrabold text-red-600">Rp {{ number_format($pendingRevenue, 0, ',', '.') }}</p>
                    <p class="mt-4 text-xs text-gray-400">Total dari {{ $unpaidInvoicesCount }} invoice tertunda</p>
                </div>

                <!-- Growth Card -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">User Base</p>
                    <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ number_format($totalCustomers) }} Users</p>
                    <div class="mt-4 grid grid-cols-2 gap-2 text-[10px] font-bold uppercase tracking-tighter">
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded">{{ $activeCustomers }} Active</span>
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded">{{ $suspendedCustomers }} Suspended</span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Growth Chart -->
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-gray-800">Revenue Analysis (Last 6 Months)</h3>
                        <div class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase">Monthly Insights</div>
                    </div>
                    <div class="h-[300px]">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Distribution Chart -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-6">Collection Status</h3>
                    <div class="h-[240px] relative">
                        <canvas id="collectionChart"></canvas>
                    </div>
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-emerald-500"></div> Paid Invoices</span>
                            <span class="font-bold text-gray-900">{{ $paymentStatusStats['Paid'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-amber-500"></div> Pending Invoices</span>
                            <span class="font-bold text-gray-900">{{ $paymentStatusStats['Unpaid'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-rose-500"></div> Overdue</span>
                            <span class="font-bold text-gray-900">{{ $paymentStatusStats['Overdue'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Block -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Recent Invoices</h3>
                    <a href="{{ route('invoices.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition uppercase tracking-widest">
                        Semua Invoice &rarr;
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">INV Number / Client</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Amount</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Due Date</th>
                                <th class="px-8 py-4 text-right"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentInvoices as $inv)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold shadow-sm shadow-indigo-200">
                                                {{ substr($inv->customer?->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 tracking-tight">{{ $inv->invoice_number }}</div>
                                                <div class="text-[11px] font-medium text-gray-500 uppercase">{{ $inv->customer?->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <span class="text-sm font-extrabold text-gray-900">Rp {{ number_format($inv->amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        @php
                                            $status = $inv->status->value ?? $inv->status;
                                            $overdue = $inv->due_date->isPast() && $status === 'unpaid';
                                        @endphp
                                        @if($status === 'paid')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-widest border border-emerald-200">Paid</span>
                                        @elseif($overdue)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-100 text-rose-800 uppercase tracking-widest border border-rose-200">Overdue</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-widest border border-amber-200">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap text-sm font-medium text-gray-500">
                                        {{ $inv->due_date->format('d M, Y') }}
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('invoices.show', $inv->id) }}" class="inline-flex items-center px-3 py-1 shadow-sm border border-gray-300 bg-white rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-gray-400 font-medium">No activity to show yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Configuration JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyLabels) !!},
                    datasets: [{
                        label: 'Gross Revenue',
                        data: {!! json_encode($monthlyRevenueData) !!},
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#4f46e5'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [5, 5] }, ticks: { font: { size: 10 } } },
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });

            // Collection Distribution Chart
            const colCtx = document.getElementById('collectionChart').getContext('2d');
            new Chart(colCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Unpaid', 'Overdue'],
                    datasets: [{
                        data: [
                            {{ $paymentStatusStats['Paid'] }},
                            {{ $paymentStatusStats['Unpaid'] }},
                            {{ $paymentStatusStats['Overdue'] }}
                        ],
                        backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>