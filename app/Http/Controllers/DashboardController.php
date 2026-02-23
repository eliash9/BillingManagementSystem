<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Service;
use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Enums\ServiceStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $activeServices = Service::where('status', ServiceStatus::Active)->count();
        $unpaidInvoicesCount = Invoice::where('status', InvoiceStatus::Unpaid)->count();
        $totalRevenue = Payment::where('status', PaymentStatus::Verified)->sum('amount');

        $recentInvoices = Invoice::with('service.customer')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalCustomers',
            'activeServices',
            'unpaidInvoicesCount',
            'totalRevenue',
            'recentInvoices'
        ));
    }
}
