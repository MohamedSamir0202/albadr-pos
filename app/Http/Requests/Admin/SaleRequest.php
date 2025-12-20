<?php

namespace App\Http\Requests\Admin;

use App\Enums\PaymentTypeEnum;
use App\Settings\AdvancedSettings;
use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $advancedSettings = app(AdvancedSettings::class);

        return [
            'client_id' => ['required', 'exists:clients,id'],
            'sale_date' => ['required', 'date'],
            'invoice_number' => ['required', 'unique:sales,invoice_number'],
            'safe_id' => ['required', 'exists:safes,id'],
            'warehouse_id' => ['required', 'exists:warehouses,id'],
            'discount_type' => ['required', 'integer'],
            'discount_value' => ['nullable', 'numeric'],

            'payment_type' => [
                'required',
                'in:' . implode(',', $advancedSettings->payment_methods_ids())
            ],
            'payment_amount' => [
                'required_if:payment_type,' . PaymentTypeEnum::debt->value,
                'numeric',
                'min:0'
            ],

            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'exists:items,id'],
            'items.*.notes' => ['nullable', 'string'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'items.*.qty' => array_merge(
                ['required'],
                $advancedSettings->allow_decimal_quantities
                    ? ['numeric', 'min:0.01']
                    : ['integer', 'min:1']
            ),
        ];
    }


    public function messages(): array
    {
        return [
            'client_id.required' => 'Please select a client.',
            'client_id.exists' => 'Selected client does not exist.',
            'sale_date.required' => 'Sale date is required.',
            'invoice_number.required' => 'Invoice number is required.',
            'invoice_number.unique' => 'Invoice number must be unique.',
            'safe_id.required' => 'Please select a safe.',
            'warehouse_id.required' => 'Please select a warehouse.',
            'payment_type.required' => 'Please select a payment type.',
            'payment_amount.required_if' => 'Payment amount is required for debt payment type.',
            'items.required' => 'At least one item is required.',
            'items.*.id.exists' => 'Selected item does not exist.',
            'items.*.qty.required' => 'Quantity is required for each item.',
            'items.*.qty.min' => 'Quantity must be at least 1.',
        ];
    }
}
