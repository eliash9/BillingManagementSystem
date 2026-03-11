<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Tagihan & Perusahaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Profil Identitas & Rekening</h3>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Company Information -->
                            <div class="space-y-6">
                                <h4 class="font-semibold text-lg text-indigo-700 border-b pb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Informasi Perusahaan
                                </h4>

                                <div class="space-y-4">
                                    <div
                                        class="bg-gray-50 p-4 rounded-lg flex items-center gap-6 border border-gray-100 mb-6">
                                        <div class="shrink-0">
                                            @if(!empty($settings['app_logo']))
                                                <img class="h-16 w-16 object-contain rounded-md border bg-white"
                                                    src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo">
                                            @else
                                                <div
                                                    class="h-16 w-16 rounded-md border bg-white flex items-center justify-center text-gray-300">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <x-input-label for="app_logo" value="Logo Aplikasi & Invoice" />
                                            <input id="app_logo" name="app_logo" type="file"
                                                class="mt-1 block w-full text-xs text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer"
                                                accept="image/*">
                                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, SVG max 2MB. Disarankan
                                                format persegi atau landscape pendek.</p>
                                        </div>
                                    </div>

                                    <div>
                                        <x-input-label for="company_name" value="Nama Perusahaan / Brand" />
                                        <x-text-input id="company_name" name="company_name" type="text"
                                            class="mt-1 block w-full" :value="old('company_name', $settings['company_name'] ?? 'Eliash')" required autofocus />
                                        <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                                    </div>

                                    <div>
                                        <x-input-label for="company_email" value="Email Perusahaan" />
                                        <x-text-input id="company_email" name="company_email" type="email"
                                            class="mt-1 block w-full" :value="old('company_email', $settings['company_email'] ?? 'halo@eliash.my.id')" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('company_email')" />
                                    </div>

                                    <div>
                                        <x-input-label for="company_phone" value="Telepon / WhatsApp" />
                                        <x-text-input id="company_phone" name="company_phone" type="text"
                                            class="mt-1 block w-full" :value="old('company_phone', $settings['company_phone'] ?? '+62 85546774992')" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('company_phone')" />
                                    </div>

                                    <div>
                                        <x-input-label for="company_address" value="Alamat Lengkap" />
                                        <textarea id="company_address" name="company_address"
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full text-sm"
                                            rows="3"
                                            required>{{ old('company_address', $settings['company_address'] ?? 'Kav Kampungbaru Ngempit Kraton Pasuruan') }}</textarea>
                                        <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Methods (Dynamic) -->
                            <div class="space-y-6" x-data="{ 
                                methods: {{ isset($settings['payment_methods']) ? json_encode($settings['payment_methods']) : '[]' }},
                                addMethod() {
                                    this.methods.push({ type: 'bank', name: '', account_number: '', account_holder: '' });
                                },
                                removeMethod(index) {
                                    this.methods.splice(index, 1);
                                }
                            }">
                                <div class="flex justify-between items-center border-b pb-2">
                                    <h4 class="font-semibold text-lg text-emerald-700 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Metode Pembayaran
                                    </h4>
                                    <button type="button" @click="addMethod()" 
                                        class="inline-flex items-center px-3 py-1 bg-emerald-100 border border-emerald-300 rounded text-xs font-semibold text-emerald-700 hover:bg-emerald-200 transition">
                                        + Tambah Rekening/E-Wallet
                                    </button>
                                </div>

                                <div class="space-y-4">
                                    <template x-for="(method, index) in methods" :key="index">
                                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg relative group">
                                            <button type="button" @click="removeMethod(index)" 
                                                class="absolute top-2 right-2 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label value="Tipe & Nama (Contoh: Bank BCA / E-Wallet OVO)" />
                                                    <div class="flex gap-2">
                                                        <select x-model="method.type" :name="'payment_methods['+index+'][type]'" 
                                                            class="mt-1 block w-1/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                                            <option value="bank">Bank</option>
                                                            <option value="ewallet">E-Wallet</option>
                                                        </select>
                                                        <input type="text" x-model="method.name" :name="'payment_methods['+index+'][name]'" 
                                                            placeholder="Nama (BCA, OVO, dll)"
                                                            class="mt-1 block w-2/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required />
                                                    </div>
                                                </div>

                                                <div>
                                                    <x-input-label value="Nomor Rekening / HP" />
                                                    <input type="text" x-model="method.account_number" :name="'payment_methods['+index+'][account_number]'" 
                                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required />
                                                </div>

                                                <div class="md:col-span-2">
                                                    <x-input-label value="Atas Nama" />
                                                    <input type="text" x-model="method.account_holder" :name="'payment_methods['+index+'][account_holder]'" 
                                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required />
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="methods.length === 0" class="text-center py-8 border-2 border-dashed border-gray-200 rounded-lg text-gray-400">
                                        <p>Belum ada metode pembayaran yang ditambahkan.</p>
                                        <button type="button" @click="addMethod()" class="mt-2 text-indigo-600 hover:underline text-sm font-medium">Tambah Sekarang</button>
                                    </div>
                                </div>

                                <!-- Hidden input to ensure old bank info is still there for now -->
                                <div class="hidden">
                                    <input type="hidden" name="bank_name" :value="methods.length > 0 ? methods[0].name : ''">
                                    <input type="hidden" name="bank_account_number" :value="methods.length > 0 ? methods[0].account_number : ''">
                                    <input type="hidden" name="bank_account_holder" :value="methods.length > 0 ? methods[0].account_holder : ''">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-6 border-gray-100">
                            <x-primary-button>
                                {{ __('Simpan Pengaturan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>