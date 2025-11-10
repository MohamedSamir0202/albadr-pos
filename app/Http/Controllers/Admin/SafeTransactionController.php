<?php

namespace App\Http\Controllers\Admin;

use App\Models\Safe;
use App\Models\SafeTransaction;
use App\Enums\SafeTransactionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SafeTransactionRequest;

class SafeTransactionController extends Controller
{
    public function index(string $safe_id)
    {
        $safe = Safe::findOrFail($safe_id);

        $transactions = SafeTransaction::where('safe_id', $safe_id)
            ->latest()
            ->paginate();

        return view('admin.safe-transactions.index', compact('safe', 'transactions'));
    }

    public function create(string $safe_id)
    {
        $safe = Safe::findOrFail($safe_id);
        $types = SafeTransactionTypeEnum::labels();

        return view('admin.safe-transactions.create', compact('safe', 'types'));
    }

    public function store(SafeTransactionRequest $request, string $safe_id)
    {
        $safe = Safe::findOrFail($safe_id);

        $data = $request->validated();
        $type = (int)$data['type'];

        $newBalance = $type === SafeTransactionTypeEnum::in->value
            ? $safe->balance + $data['amount']
            : $safe->balance - $data['amount'];

        SafeTransaction::create([
            'safe_id'        => $safe_id,
            'user_id'        => auth()->id(),
            'type'           => $type,
            'amount'         => $data['amount'],
            'balance_after'  => $newBalance,
            'description'    => $data['description'] ?? '',
        ]);


        $safe->update(['balance' => $newBalance]);

        session()->flash('success', 'Transaction recorded successfully.');
        return redirect()->route('admin.safe-transactions.index', $safe_id);
    }
}
