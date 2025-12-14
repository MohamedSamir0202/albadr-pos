<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Item;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function itemTransactions(Request $request)
    {
        $items = Item::all();
        $clients = Client::all();

        $transactions = Sale::with(['items', 'client', 'warehouseTransactions'])->where(function ($query) use($request){
            // write your filter here : item_id, client_id, date_from, date_to
            if ($request->item_id){
                $query->whereHas('items', function ($q) use ($request){
                    $q->where('item_id', $request->item_id);
                });
            }
            if ($request->client_id){
                $query->where('client_id', $request->client_id);
            }
            if ($request->date_from){
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->date_to){
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        })->paginate();


        return view('admin.reports.item_transactions', compact('items', 'clients', 'transactions'));
    }
}
