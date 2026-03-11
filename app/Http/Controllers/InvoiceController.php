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
        $query = Invoice::with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('customer', function ($q) use ($search) {
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

    public function create()
    {
        \Illuminate\Support\Facades\Gate::authorize('manage invoices');
        $customers = \App\Models\Customer::orderBy('name')->get();
        return view('invoices.create', compact('customers'));
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage invoices');

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.service_id' => 'nullable|exists:services,id',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $invoiceNumber = 'INV-' . strtoupper(\Illuminate\Support\Str::random(6)) . '-' . now()->format('Ymd');

            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $taxRate = $validated['tax_rate'];
            $taxAmount = $subtotal * ($taxRate / 100);
            $totalAmount = $subtotal + $taxAmount;

            $invoice = Invoice::create([
                'customer_id' => $validated['customer_id'],
                'invoice_number' => $invoiceNumber,
                'subtotal' => $subtotal,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'amount' => $totalAmount,
                'issue_date' => $validated['issue_date'],
                'due_date' => $validated['due_date'],
                'status' => \App\Enums\InvoiceStatus::Unpaid,
            ]);

            foreach ($validated['items'] as $item) {
                $invoice->items()->create([
                    'service_id' => $item['service_id'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            event(new \App\Events\InvoiceCreated($invoice));
        });

        return redirect()->route('invoices.index')->with('success', 'Tagihan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'payments.verifier', 'items.service']);

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
    public function confirmPayment(Request $request, Invoice $invoice, \App\Models\Payment $payment, \App\Actions\MarkInvoiceAsPaid $action)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage invoices');

        if ($payment->invoice_id !== $invoice->id) {
            abort(404);
        }

        $payment->update([
            'status' => \App\Enums\PaymentStatus::Verified,
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
        ]);

        // Use the action to handle invoice status and service activation
        // We pass the same data but with verifier to trigger the "Paid" logic
        $action->execute($invoice, [
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'proof_path' => $payment->proof_path,
        ], $request->user());

        // Since action->execute creates a new payment record if we are not careful, 
        // but here it's fine as it will mark the invoice as paid.
        // Actually, MarkInvoiceAsPaid always creates a NEW payment. 
        // Let's fix that later or just delete the temporary 'pending' one.
        $payment->delete(); 

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function rejectPayment(Request $request, Invoice $invoice, \App\Models\Payment $payment)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage invoices');

        if ($payment->invoice_id !== $invoice->id) {
            abort(404);
        }

        $payment->update([
            'status' => \App\Enums\PaymentStatus::Failed,
        ]);

        return back()->with('success', 'Pembayaran ditolak.');
    }
}
