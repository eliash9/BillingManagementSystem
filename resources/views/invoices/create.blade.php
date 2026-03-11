<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Tagihan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-100">
                    <form method="POST" action="{{ route('invoices.store') }}" x-data="invoiceForm()">
                        @csrf

                        <!-- Invoice Headers -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <div>
                                <x-input-label for="customer_id" value="Pilih Pelanggan" />
                                <select id="customer_id" name="customer_id"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required>
                                    <option value="" disabled selected>Pilih Pelanggan...</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} {{ $customer->company ? '('.$customer->company.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('customer_id')" />
                            </div>

                            <div>
                                <x-input-label for="issue_date" value="Tanggal Terbit" />
                                <x-text-input id="issue_date" name="issue_date" type="date" class="mt-1 block w-full"
                                    :value="old('issue_date', now()->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('issue_date')" />
                            </div>

                            <div>
                                <x-input-label for="due_date" value="Tanggal Jatuh Tempo" />
                                <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full"
                                    :value="old('due_date', now()->addDays(7)->format('Y-m-d'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                            </div>

                            <div>
                                <x-input-label for="tax_rate" value="Pajak / PPN (%)" />
                                <x-text-input id="tax_rate" name="tax_rate" type="number" step="0.01" min="0" max="100"
                                    class="mt-1 block w-full" x-model.number="taxRate" required />
                                <x-input-error class="mt-2" :messages="$errors->get('tax_rate')" />
                            </div>
                        </div>

                        <!-- Invoice Items (Multi-Item) -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Detail Item Tagihan</h3>
                                <button type="button" @click="addItem()"
                                    class="px-4 py-2 bg-indigo-50 text-indigo-700 font-semibold rounded-md hover:bg-indigo-100 text-sm border border-indigo-200">
                                    + Tambah Item
                                </button>
                            </div>

                            <x-input-error class="mt-2" :messages="$errors->get('items')" />

                            <div class="bg-gray-50 border border-gray-200 rounded-md overflow-hidden">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-gray-200 text-gray-600">
                                        <tr>
                                            <th class="px-4 py-3 w-5/12">Deskripsi Item</th>
                                            <th class="px-4 py-3 w-2/12">Kuantitas</th>
                                            <th class="px-4 py-3 w-3/12">Harga Satuan (Rp)</th>
                                            <th class="px-4 py-3 w-2/12 text-right">Total (Rp)</th>
                                            <th class="px-4 py-3 w-12 text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(item, index) in items" :key="index">
                                            <tr class="border-b border-gray-200 bg-white">
                                                <td class="p-4">
                                                    <input type="text" x-model="item.description"
                                                        :name="`items[${index}][description]`"
                                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm"
                                                        placeholder="Contoh: Biaya Langganan Bulanan" required>
                                                </td>
                                                <td class="p-4">
                                                    <input type="number" x-model.number="item.quantity"
                                                        :name="`items[${index}][quantity]`" min="1" step="0.01"
                                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm"
                                                        required>
                                                </td>
                                                <td class="p-4">
                                                    <input type="number" x-model.number="item.unit_price"
                                                        :name="`items[${index}][unit_price]`" min="0" step="0.01"
                                                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm sm:text-sm"
                                                        required>
                                                </td>
                                                <td class="p-4 text-right font-semibold text-gray-700">
                                                    <span
                                                        x-text="formatCurrency(item.quantity * item.unit_price)"></span>
                                                </td>
                                                <td class="p-4 text-center">
                                                    <button type="button" @click="removeItem(index)"
                                                        class="text-red-500 hover:text-red-700"
                                                        :disabled="items.length === 1"
                                                        :class="{'opacity-50 cursor-not-allowed': items.length === 1}">
                                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Summary Totals -->
                        <div class="flex justify-end">
                            <div class="w-full md:w-1/3 bg-gray-50 rounded-lg border border-gray-200 p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Sub Total:</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="'Rp ' + formatCurrency(subtotal)"></span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Pajak (<span x-text="taxRate"></span>%):</span>
                                    <span class="font-semibold text-gray-800"
                                        x-text="'Rp ' + formatCurrency(taxAmount)"></span>
                                </div>
                                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                                    <span class="text-lg font-bold text-gray-900">Total Tagihan:</span>
                                    <span class="text-xl font-bold text-indigo-700"
                                        x-text="'Rp ' + formatCurrency(total)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                            <a href="{{ route('invoices.index') }}"
                                class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-4 font-medium text-sm">Batal</a>
                            <x-primary-button class="px-8 bg-indigo-600 hover:bg-indigo-700">
                                {{ __('Simpan & Buat Tagihan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('invoiceForm', () => ({
                    taxRate: {{ old('tax_rate', 0) }},
                    items: [
                        { description: '', quantity: 1, unit_price: 0 }
                    ],

                    init() {
                        // Try to restore old input if validation failed
                        let oldItems = @json(old('items', []));
                        if (oldItems.length > 0) {
                            this.items = oldItems;
                        }
                    },

                    addItem() {
                        this.items.push({ description: '', quantity: 1, unit_price: 0 });
                    },

                    removeItem(index) {
                        if (this.items.length > 1) {
                            this.items.splice(index, 1);
                        }
                    },

                    get subtotal() {
                        return this.items.reduce((sum, item) => {
                            let q = parseFloat(item.quantity) || 0;
                            let p = parseFloat(item.unit_price) || 0;
                            return sum + (q * p);
                        }, 0);
                    },

                    get taxAmount() {
                        let rate = parseFloat(this.taxRate) || 0;
                        return this.subtotal * (rate / 100);
                    },

                    get total() {
                        return this.subtotal + this.taxAmount;
                    },

                    formatCurrency(value) {
                        if (!value) return '0';
                        return Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                }))
            })
        </script>
    @endpush
</x-app-layout>