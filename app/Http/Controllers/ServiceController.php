<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        if (in_array($sortField, ['name', 'price', 'next_due_date', 'status'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest();
        }

        $services = $query->paginate(10)->withQueryString();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        $customers = \App\Models\Customer::all();
        return view('services.create', compact('customers'));
    }

    public function store(\App\Http\Requests\StoreServiceRequest $request, \App\Actions\CreateServiceSubscription $action, \App\Actions\GenerateInvoiceForService $invoiceAction)
    {
        $customer = \App\Models\Customer::findOrFail($request->customer_id);

        $service = $action->execute($customer, $request->validated());

        if ($request->has('generate_invoice') && $request->generate_invoice) {
            $invoiceAction->execute($service);
            return redirect()->route('services.index')->with('success', 'Service created and first invoice generated successfully.');
        }

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function generateInvoice(Service $service, \App\Actions\GenerateInvoiceForService $invoiceAction)
    {
        if (in_array($service->status, [\App\Enums\ServiceStatus::Suspended, \App\Enums\ServiceStatus::Due])) {
            return back()->withErrors(['error' => 'Cannot generate invoice for suspended or already due service.']);
        }

        $invoiceAction->execute($service);

        return back()->with('success', 'Invoice generated successfully.');
    }
}
