<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Models\WarehouseTransaction;
use App\Services\FileService;
use App\Enums\ItemStatusEnum;
use App\Enums\WarehouseTransactionTypeEnum;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index()
    {
        $items = Item::with(['category', 'unit'])->latest()->paginate(15);
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        $units = Unit::select('id', 'name')->get();
        $warehouses = Warehouse::select('id', 'name')->get();
        $itemStatuses = ItemStatusEnum::labels();

        return view('admin.items.create', compact(
            'itemStatuses',
            'categories',
            'units',
            'warehouses'
        ));
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(ItemRequest $request)
    {

        $item = Item::create($request->except(['quantity', 'warehouse_id']));
        FileService::upload($request, 'photo', $item, 'item_photo', 'items');
       // FileService::uploadGallery($request, 'gallery', $item, 'item_gallery', 'items');
        if ($request->filled('warehouse_id') && $request->filled('quantity')) {
            $warehouse = Warehouse::findOrFail($request->warehouse_id);
            $warehouse->items()->attach($item->id, [
                'quantity' => $request->quantity,
            ]);
            WarehouseTransaction::create([
                'warehouse_id' => $warehouse->id,
                'item_id' => $item->id,
                'transaction_type' => WarehouseTransactionTypeEnum::init,
                'reference_id' => $item->id,
                'reference_type' => Item::class,
                'user_id' => auth()->id(),
                'quantity' => $request->quantity,
                'quantity_after' => $request->quantity,
                'description' => 'Initial stock added for item creation.',
            ]);
        }


        session()->flash('success', 'Item created successfully.');
        return redirect()->route('admin.items.index');
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item)
    {
        $categories = Category::select('id', 'name')->get();
        $units = Unit::select('id', 'name')->get();
        $warehouses = Warehouse::select('id', 'name')->get();
        $itemStatuses = ItemStatusEnum::labels();

        return view('admin.items.edit', compact(
            'item',
            'itemStatuses',
            'categories',
            'units',
            'warehouses'
        ));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(ItemRequest $request, Item $item)
    {
        $item->update($request->except(['quantity', 'warehouse_id']));

        FileService::upload($request, 'photo', $item, 'item_photo', 'items');
       // FileService::uploadGallery($request, 'gallery', $item, 'item_gallery', 'items');

        session()->flash('success', 'Item updated successfully.');
        return redirect()->route('admin.items.index');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        session()->flash('success', 'Item deleted successfully.');
        return redirect()->route('admin.items.index');
    }
}
