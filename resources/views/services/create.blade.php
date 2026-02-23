<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('services.store') }}">
                        @csrf

                        <!-- Customer -->
                        <div class="mb-4">
                            <label for="customer_id" class="block font-medium text-sm text-gray-700">Customer</label>
                            <select id="customer_id"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                name="customer_id" required>
                                <option value="">Select a Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block font-medium text-sm text-gray-700">Service Name</label>
                            <input id="name"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                type="text" name="name" :value="old('name')" required />
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                            <textarea id="description"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                name="description" rows="3">{{ old('description') }}</textarea>
                        </div>

                        <!-- Price -->
                        <div class="mb-4">
                            <label for="price" class="block font-medium text-sm text-gray-700">Price (IDR)</label>
                            <input id="price"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                type="number" name="price" :value="old('price', 0)" min="0" required />
                        </div>

                        <!-- Billing Cycle -->
                        <div class="mb-4">
                            <label for="billing_cycle" class="block font-medium text-sm text-gray-700">Billing
                                Cycle</label>
                            <select id="billing_cycle"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                name="billing_cycle" required>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div class="mb-4">
                            <label for="start_date" class="block font-medium text-sm text-gray-700">Start Date</label>
                            <input id="start_date"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                type="date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}"
                                required />
                        </div>

                        <!-- Generate First Invoice Immediately -->
                        <div class="block mt-4 mb-4">
                            <label for="generate_invoice" class="inline-flex items-center">
                                <input id="generate_invoice" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    name="generate_invoice" value="1" checked>
                                <span
                                    class="ms-2 text-sm text-gray-600">{{ __('Generate First Invoice Immediately') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>