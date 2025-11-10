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
     */
    public function rules(): array
    {
        $itemId = $this->route('item');
        $isUpdating = !is_null($itemId);
        $requiredOrNullable = $isUpdating ? 'nullable' : 'required';

        return [
            'name' => 'required|string|unique:items,name,' . $itemId,
            'item_code' => 'nullable|string|unique:items,item_code,' . $itemId,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'quantity' => $requiredOrNullable . '|numeric|min:0|max:999999.99',
            'category_id' => 'required|integer|exists:categories,id',
            'unit_id' => 'required|integer|exists:units,id',
            'is_shown_in_store' => 'required|in:0,1',
            'minimum_stock' => 'required|numeric|min:0|max:999999.99',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery' => 'nullable|array|max:5',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'warehouse_id' => $requiredOrNullable . '|integer|exists:warehouses,id',
        ];
    }
}
