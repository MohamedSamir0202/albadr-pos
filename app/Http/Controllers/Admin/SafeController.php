<?php

namespace App\Http\Controllers\Admin;

use App\Models\Safe;
use Illuminate\Http\Request;
use App\Enums\SafeStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SafeRequest;

class SafeController extends Controller
{
    public function index()
    {
        $safes = Safe::paginate();

        return view('admin.safes.index', compact('safes'));
    }

    public function create()
    {
        $safeStatuses = SafeStatusEnum::labels();
        return view('admin.safes.create');
    }

    public function store(SafeRequest $request)
    {
        Safe::create($request->validated());

        session()->flash('success', 'Safe created successfully.');
        return redirect()->route('admin.safes.index');
    }

    public function show(string $id)
    {
        $safe = Safe::findOrFail($id);

        $transactions = $safe->transactions()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.safes.show', compact('safe', 'transactions'));
    }


    public function edit(string $id)
    {
        $safe = Safe::findOrFail($id);
        $safeStatuses = SafeStatusEnum::labels();
        return view('admin.safes.edit', compact('safe'));
    }

    public function update(SafeRequest $request, string $id)
    {
        $safe = Safe::findOrFail($id);
        $data = $request->validated();
        $safe->update($data);
        session()->flash('success', 'Safe updated successfully.');
        return redirect()->route('admin.safes.index');
    }

    public function destroy(string $id)
    {
        $safe = Safe::findOrFail($id);
        $safe->delete();
        session()->flash('success', 'Safe deleted successfully.');
        return redirect()->route('admin.safes.index');
    }
}

