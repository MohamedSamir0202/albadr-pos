<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ItemStatusEnum;
use App\Enums\SafeStatusEnum;
use App\Enums\UnitStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Item;
use App\Models\Safe;
use App\Models\Unit;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function create()
    {
        $clients = Client::all();
        $safes = Safe::where('status', SafeStatusEnum::active)->get();
        $units = Unit::where('status', UnitStatusEnum::active)->get();
        $items = Item::where('status', ItemStatusEnum::active)->get();
        return view('admin.sales.create', compact('clients', 'safes', 'units', 'items'));
    }
}
    