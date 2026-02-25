<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        \Illuminate\Support\Facades\Gate::authorize('manage settings');

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage settings');

        $data = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_account_holder' => 'nullable|string|max:255',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('settings.edit')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
