<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SafeTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:1,-1',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ];
    }
}
