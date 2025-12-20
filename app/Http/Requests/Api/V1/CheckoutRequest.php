<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_name'    => 'required|string|max:100',
            'shipping_address' => 'required|string|max:500',
            'shipping_phone'   => 'required|string|max:20',
        ];
    }
}
