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
                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Company Information -->
                            <div class="space-y-6">
                                <h4 class="font-semibold text-lg text-indigo-700 border-b pb-2">Informasi Perusahaan
                                    (Invoice Sender)</h4>

                                <div class="space-y-4">
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
                                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                                            rows="3"
                                            required>{{ old('company_address', $settings['company_address'] ?? 'Kav Kampungbaru Ngempit Kraton Pasuruan') }}</textarea>
                                        <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Information -->
                            <div class="space-y-6">
                                <h4 class="font-semibold text-lg text-emerald-700 border-b pb-2">Informasi Rekening Bank
                                    Pembayaran</h4>

                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="bank_name" value="Nama Bank (Cth: BCA, Mandiri, BSI)" />
                                        <x-text-input id="bank_name" name="bank_name" type="text"
                                            class="mt-1 block w-full" :value="old('bank_name', $settings['bank_name'] ?? 'Bank Central Asia (BCA)')" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('bank_name')" />
                                    </div>

                                    <div>
                                        <x-input-label for="bank_account_number" value="Nomor Rekening" />
                                        <x-text-input id="bank_account_number" name="bank_account_number" type="text"
                                            class="mt-1 block w-full" :value="old('bank_account_number', $settings['bank_account_number'] ?? '123 456 7890')" required />
                                        <x-input-error class="mt-2" :messages="$errors->get('bank_account_number')" />
                                    </div>

                                    <div>
                                        <x-input-label for="bank_account_holder" value="Atas Nama (Pemilik Rekening)" />
                                        <x-text-input id="bank_account_holder" name="bank_account_holder" type="text"
                                            class="mt-1 block w-full" :value="old('bank_account_holder', $settings['bank_account_holder'] ?? 'PT SaaS Company Indonesia')"
                                            required />
                                        <x-input-error class="mt-2" :messages="$errors->get('bank_account_holder')" />
                                    </div>
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