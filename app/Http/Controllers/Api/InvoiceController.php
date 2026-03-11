<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Requests\StorePaymentRequest;
use App\Actions\MarkInvoiceAsPaid;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoices = Invoice::with('customer')->paginate($request->get('limit', 15));

        return response()->json($invoices);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return response()->json($invoice->load('customer', 'payments.verifier'));
    }

    /**
     * Process a payment for the invoice.
     */
    public function pay(StorePaymentRequest $request, Invoice $invoice, MarkInvoiceAsPaid $action)
    {
        $verifier = $request->user();

        $data = $request->validated();

        if ($request->hasFile('proof_path')) {
            $data['proof_path'] = $request->file('proof_path')->store('payments', 'public');
        }

        $payment = $action->execute($invoice, $data, $verifier);

        return response()->json([
            'message' => 'Payment processed successfully',
            'data' => $payment
        ], 201);
    }
}
