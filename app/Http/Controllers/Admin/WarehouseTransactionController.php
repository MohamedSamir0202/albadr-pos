<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WarehouseTransaction;
use App\Models\Warehouse;
use App\Models\Item;
use App\Enums\WarehouseTransactionTypeEnum;
use Illuminate\Http\Request;

class WarehouseTransactionController extends Controller
{
    public function index(Request $request)
    {
        $warehouses = Warehouse::all();
        $items = Item::all();
        $transactionTypes = WarehouseTransactionTypeEnum::labels();

        $transactions = WarehouseTransaction::with(['item'])
            ->when($request->warehouse_id, fn($q) => $q->where('warehouse_id', $request->warehouse_id))
            ->when($request->item_id, fn($q) => $q->where('item_id', $request->item_id))
            ->when($request->transaction_type, fn($q) => $q->where('transaction_type', $request->transaction_type))
            ->when($request->from_date, fn($q) => $q->whereDate('created_at', '>=', $request->from_date))
            ->when($request->to_date, fn($q) => $q->whereDate('created_at', '<=', $request->to_date))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.warehouses.transactions.index', compact(
            'transactions', 'warehouses', 'items', 'transactionTypes'
        ));
    }
}
