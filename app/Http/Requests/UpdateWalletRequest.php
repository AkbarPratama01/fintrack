<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWalletRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,e-wallet,credit-card,savings',
            'currency' => 'required|string|size:3',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'wallet name',
            'type' => 'wallet type',
            'currency' => 'currency',
            'description' => 'description',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a wallet name.',
            'type.required' => 'Please select a wallet type.',
            'type.in' => 'Invalid wallet type selected.',
            'currency.required' => 'Please select a currency.',
            'currency.size' => 'Currency code must be 3 characters.',
        ];
    }
}
