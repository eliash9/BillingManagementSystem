<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Customer;
use App\Http\Requests\StoreServiceRequest;
use App\Actions\CreateServiceSubscription;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $services = Service::with('customer')->paginate($request->get('limit', 15));

        return response()->json($services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request, CreateServiceSubscription $action)
    {
        $customer = Customer::findOrFail($request->customer_id);

        $service = $action->execute($customer, $request->validated());

        return response()->json([
            'message' => 'Service created successfully',
            'data' => $service
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return response()->json($service->load('customer', 'invoices'));
    }
}
