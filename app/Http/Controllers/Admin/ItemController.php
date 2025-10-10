<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Enums\ItemStatusEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ItemRequest;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::all();
        return view('admin.items.index',compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        $units = Unit::select('id', 'name')->get();
        $itemStatuses = ItemStatusEnum::labels();
        return view('admin.items.create', compact('itemStatuses', 'categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemRequest $request)
    {
        //dd($request->all());
        Item::create($request->validated());
        session()->flash('success', 'Item created successfully.');
        return redirect()->route('admin.items.index');
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        $itemStatuses = ItemStatusEnum::labels();
        $categories = Category::select('id', 'name')->get();
        $units = Unit::select('id', 'name')->get();
        return view('admin.items.edit', compact('item', 'itemStatuses', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemRequest $request, string $id)
    {
        $item = Item::findOrFail($id);
        $item->update($request->validated());
        session()->flash('success', 'Item updated successfully.');
        return redirect()->route('admin.items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
        {
            $item = Item::findOrFail($id);
            $item->delete();

            return redirect()
                ->route('admin.items.index')
                ->with('success', 'Item deleted successfully.');
        }

}
