@extends('admin.layouts.app', [
    'pageName' => 'Items',
])

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add New Item</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.items.store') }}" id="main-form">
                    @csrf

                    <!-- Item Code -->
                    <div class="form-group">
                        <label for="item_code">Item Code</label>
                        <input type="text" name="item_code" id="item_code"
                               class="form-control @error('item_code') is-invalid @enderror"
                               value="{{ old('item_code') }}" placeholder="Enter item code">
                        @error('item_code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <!-- Item Name -->
                    <div class="form-group">
                        <label for="name">Item Name</label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Enter item name">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3" placeholder="Enter description">{{ old('description') }}</textarea>
                        @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <!-- Price / Quantity / Minimum Stock -->
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="price">Price</label>
                            <input type="number" step="0.01" name="price" id="price"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price') }}" placeholder="Enter price">
                            @error('price') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity') }}" placeholder="Enter quantity">
                            @error('quantity') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-4">
                            <label for="minimum_stock">Minimum Stock</label>
                            <input type="number" name="minimum_stock" id="minimum_stock"
                                   class="form-control @error('minimum_stock') is-invalid @enderror"
                                   value="{{ old('minimum_stock') }}" placeholder="Enter minimum stock">
                            @error('minimum_stock') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Category / Unit -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id"
                                    class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="unit_id">Unit</label>
                            <select name="unit_id" id="unit_id"
                                    class="form-control @error('unit_id') is-invalid @enderror">
                                <option value="">-- Select Unit --</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label>Status</label>
                        @foreach($itemStatuses as $value => $label)
                            <div class="form-check">
                                <input type="radio" name="status" id="status_{{ $value }}" value="{{ $value }}"
                                       class="form-check-input" {{ old('status') == $value ? 'checked' : '' }}>
                                <label for="status_{{ $value }}" class="form-check-label">{{ $label }}</label>
                            </div>
                        @endforeach
                        @error('status') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Show in Store -->
                    <div class="form-group">
                        <label for="is_shown_in_store">Show in Store</label>
                        <select name="is_shown_in_store" id="is_shown_in_store"
                                class="form-control @error('is_shown_in_store') is-invalid @enderror">
                            <option value="1" {{ old('is_shown_in_store') == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('is_shown_in_store') == 0 ? 'selected' : '' }}>No</option>
                        </select>
                        @error('is_shown_in_store') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                </form>
            </div>
            <div class="card-footer">
                <x-form-submit text="Save" form="main-form"></x-form-submit>
                <a href="{{ route('admin.items.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
</div>
@endsection
