<?php

namespace App\Http\Controllers\Admin;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Services\FileService;
use App\Enums\WarehouseStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\WarehouseRequest;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::paginate(10);
        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        $warehouseStatuses = WarehouseStatusEnum::labels();
        return view('admin.warehouses.create', compact('warehouseStatuses'));
    }

    public function store(WarehouseRequest $request)
    {
        $warehouse = Warehouse::create($request->validated());

       FileService::upload($request, 'image',
        $warehouse, 'warehouse_photo', 'warehouses');

        session()->flash('success', 'Warehouse created successfully.');
        return redirect()->route('admin.warehouses.index');
    }

    public function show(string $id)
    {
        $warehouse = Warehouse::with([
            'image',
            'items' => function ($query) {
                $query->withPivot(['quantity']);
            },
        ])->findOrFail($id);

        $transactions = \App\Models\WarehouseTransaction::with('user')
            ->where('warehouse_id', $warehouse->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.warehouses.show', compact('warehouse', 'transactions'));
    }



    public function edit(string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouseStatuses = WarehouseStatusEnum::labels();
        return view('admin.warehouses.edit', compact('warehouse', 'warehouseStatuses'));
    }

    public function update(WarehouseRequest $request, string $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($request->validated());

        FileService::update($request, 'image', $warehouse,
         'warehouse_photo', 'warehouses');


        session()->flash('success', 'Warehouse updated successfully.');
        return redirect()->route('admin.warehouses.index');
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        FileService::delete($warehouse);

        $warehouse->delete();

        return redirect()->route('admin.warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }
}
