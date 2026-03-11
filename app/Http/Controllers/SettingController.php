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

        if (isset($settings['payment_methods'])) {
            $settings['payment_methods'] = json_decode($settings['payment_methods'], true);
        }

        return view('settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('manage settings');

        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:255',
            'bank_account_holder' => 'nullable|string|max:255',
            'payment_methods' => 'nullable|array',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('logos', 'public');
            Setting::updateOrCreate(
                ['key' => 'app_logo'],
                ['value' => $path]
            );
        }

        // Exclude app_logo from the loop since it's already handled
        unset($validated['app_logo']);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : $value]
            );
        }

        return redirect()->route('settings.edit')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
