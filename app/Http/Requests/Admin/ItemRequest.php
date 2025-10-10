<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
        'item_code' => 'required|string|max:25',
        'description' => 'required|string|max:255',
        'price' => 'required|numeric',
        'quantity' => 'required|numeric',
        'is_shown_in_store' => 'required|boolean',
        'unit_id' => 'required|exists:units,id',
        'category_id' => 'required|exists:categories,id',
        'minimum_stock' => 'required|integer',
        'status' => 'required|in:1,2',
    ];
    }
}
