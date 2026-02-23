<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('service.customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('service.customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        if (in_array($sortField, ['invoice_number', 'amount', 'due_date', 'status'])) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->latest();
        }

        $invoices = $query->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['service.customer', 'payments.verifier']);

        return view('invoices.show', compact('invoice'));
    }

    public function pay(\App\Http\Requests\StorePaymentRequest $request, Invoice $invoice, \App\Actions\MarkInvoiceAsPaid $action)
    {
        $verifier = $request->user();

        $data = $request->validated();

        if ($request->hasFile('proof_path')) {
            $data['proof_path'] = $request->file('proof_path')->store('payments', 'public');
        }

        $action->execute($invoice, $data, $verifier);

        return back()->with('success', 'Payment recorded successfully.');
    }
}
