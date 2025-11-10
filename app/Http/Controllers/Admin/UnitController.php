<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Enums\UnitStatusEnum;
use App\Enums\UserStatusEnum;
use App\Services\FileService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UnitRequest;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units =Unit::all();
        return view('admin.units.index',compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $unitStatuses = UnitStatusEnum::labels();
        return view('admin.units.create', compact('unitStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitRequest $request)
    {
        $unit = Unit::create($request->validated());
        FileService::upload($request, 'image', $unit,
         'unit_photo', 'units');

        session()->flash('success', 'Unit created successfully.');
        return redirect()->route('admin.units.index');
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unitStatuses = UserStatusEnum::labels();
        return view('admin.units.edit', compact('unit', 'unitStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitRequest $request, string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->update($request->validated());
        session()->flash('success', 'Unit updated successfully.');
        return redirect()->route('admin.units.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
        {
            $item = Unit::findOrFail($id);
            $item->delete();

            return redirect()
                ->route('admin.units.index')
                ->with('success', 'Item deleted successfully.');
        }
}
