<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Enums\ServiceStatus;
use App\Enums\CustomerStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', CustomerStatus::Active)->count();
        $suspendedCustomers = Customer::where('status', CustomerStatus::Suspended)->count();
        
        $activeServices = Service::where('status', ServiceStatus::Active)->count();
        $suspendedServices = Service::where('status', ServiceStatus::Suspended)->count();
        $unpaidInvoicesCount = Invoice::where('status', InvoiceStatus::Unpaid)->count();
        
        // Revenue Metrics
        $totalRevenue = Payment::where('status', PaymentStatus::Verified)->sum('amount');
        $pendingRevenue = Invoice::where('status', InvoiceStatus::Unpaid)->sum('amount');
        
        // MRR Calculation (Sum of active services' monthly-equivalent prices)
        // For simplicity, we'll just sum the periodic price of all active services.
        $mrr = Service::where('status', ServiceStatus::Active)->sum('price');

        // Recent Invoices
        $recentInvoices = Invoice::with('customer')
            ->latest()
            ->take(5)
            ->get();

        // Chart Data: Revenue per Month (Last 6 Months)
        $monthlyRevenueData = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyLabels[] = $month->format('M Y');
            $monthlyRevenueData[] = Payment::where('status', PaymentStatus::Verified)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
        }

        // Chart Data: Payment Status Distribution
        $paymentStatusStats = [
            'Paid' => Invoice::where('status', InvoiceStatus::Paid)->count(),
            'Unpaid' => Invoice::where('status', InvoiceStatus::Unpaid)->count(),
            'Overdue' => Invoice::where('due_date', '<', now())->where('status', InvoiceStatus::Unpaid)->count(),
        ];

        return view('dashboard', compact(
            'totalCustomers',
            'activeCustomers',
            'suspendedCustomers',
            'activeServices',
            'suspendedServices',
            'unpaidInvoicesCount',
            'totalRevenue',
            'pendingRevenue',
            'mrr',
            'recentInvoices',
            'monthlyRevenueData',
            'monthlyLabels',
            'paymentStatusStats'
        ));
    }
}
