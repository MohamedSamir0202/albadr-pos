<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DiscountTypeEnum;
use App\Enums\ItemStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\SafeStatusEnum;
use App\Enums\SafeTransactionTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Enums\UnitStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaleRequest;
use App\Models\Category;
use App\Models\Client;
use App\Models\Item;
use App\Models\Safe;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\Warehouse;
use App\Services\SafeService;
use App\Services\StockManageService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @return Factory|View|\Illuminate\View\View
     */
    public function create()
    {
        $clients = Client::all();
        //todo : from settings
        $safes = Safe::where('status', SafeStatusEnum::active)->get();
        $units = Unit::where('status', UnitStatusEnum::active)->get();
        $items = Item::where('status', ItemStatusEnum::active)->get();
        $warehouses = Warehouse::all();
        $discountTypes = DiscountTypeEnum::labels();
        return view(
            'admin.returns.create',
            compact('clients', 'safes', 'units', 'items', 'discountTypes', 'warehouses')
        );
    }

    public function store(SaleRequest $request)
    {
        DB::beginTransaction();
        //dd($request->all());
        $data = $request->validated();
        $data['type'] = SaleTypeEnum::return->value;
        $sale = auth()->user()->returns()->create($data);// db
        $total = $this->attachItems($sale, $request);
        $this->updateSaleTotals($sale, $total, $request);

        // update safe & transaction
        (new SafeService)->outTransaction(
            $sale,
            $sale->paid_amount,
            'Sale Return Payment, Invoice #: ' . $sale->invoice_number);
        $this->updateClientAccountBalance($sale);

        DB::commit();
        return back();
    }

    /**
     * @param Sale $sale
     * @return void
     */
    private function updateClientAccountBalance(Sale $sale): void
    {
        $credit = $sale->paid_amount;
        $debit = $sale->net_amount;
        $balance = $credit - $debit;
        if ($balance != 0) {
            // client account update
            $sale->client->increment('balance', $balance);
        }
        //-200 = 1000 - 1200
        $sale->clientAccountTransaction()->create([
            'user_id' => auth()->user()->id,
            'client_id' => $sale->client_id,
            'credit' => $credit,
            'debit' => $debit,
            'balance' => $balance,
            'balance_after' => $sale->client->fresh()->balance,
            'description' => 'Sale Return Remaining Amount, Invoice #: ' . $sale->invoice_number,
        ]);
    }

    /**
     * @param SaleRequest $request
     * @param Sale $sale
     * @return float
     */
    private function attachItems(Sale $sale, SaleRequest $request): float
    {
        $total = 0;
        foreach ($request->items as $id => $item) {
            $queriedItem = Item::find($id);
            $totalPrice = $queriedItem->price * $item['qty'];
            $sale->items()->attach([// db
                $item['id'] => [
                    'unit_price' => $item['price'],
                    'quantity' => $item['qty'],
                    'total_price' => $totalPrice,
                    'notes' => $item['notes'],
                ]
            ]);
            // stock update
//            $queriedItem->decrement('quantity', $item['qty']);
            (new StockManageService())->increaseStock($queriedItem, $request->warehouse_id, $item['qty'], $sale);
            $total += $totalPrice;
        }
        return $total;
    }

    /**
     * @param SaleRequest $request
     * @param float|int $total
     * @return float|int|mixed
     */
    private function calculateDiscount(SaleRequest $request, float|int $total): mixed
    {
        if ($request->discount_type == DiscountTypeEnum::percentage->value) {
            $discount = $total * ($request->discount / 100);
        } else {
            $discount = $request->discount;
        }
        return $discount;
    }

    /**
     * @param float|int $total
     * @param SaleRequest $request
     * @param Sale $sale
     * @return void
     */
    private function updateSaleTotals(Sale $sale, float|int $total, SaleRequest $request): void
    {
        $discount = $this->calculateDiscount($request, $total);
        $net = $total - $discount;
        if ($request->payment_type == PaymentTypeEnum::debt->value) {
            $paid = $request->payment_amount;
        } else {
            $paid = $net;
        }
        $remaining = $net - $paid; // could be -ve ?
        $sale->total = $total;
        $sale->discount = $discount;
        $sale->net_amount = $net;
        $sale->paid_amount = $paid;
        $sale->remaining_amount = $remaining;
        $sale->save();//db
    }

    //
}
