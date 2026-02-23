<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For simplicity, assuming both admin and customer can submit payment proof
        return $this->user()->can('manage payments') || $this->user()->can('view payments');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:255',
            'proof_path' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];
    }
}
