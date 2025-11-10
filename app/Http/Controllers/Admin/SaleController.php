<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use App\Models\Safe;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\Client;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Enums\ItemStatusEnum;
use App\Enums\SafeStatusEnum;
use App\Enums\UnitStatusEnum;
use App\Services\SafeService;
use App\Enums\PaymentTypeEnum;
use App\Enums\DiscountTypeEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Services\StockManageService;
use App\Enums\SafeTransactionTypeEnum;
use Illuminate\Contracts\View\Factory;
use App\Http\Requests\Admin\SaleRequest;
use App\Services\WarehouseTransactionService;

class SaleController extends Controller
{

    public function index(Request $request)
{
    $sales = Sale::with(['client', 'user', 'safe'])
        ->when($request->client_id, fn($q) => $q->where('client_id', $request->client_id))
        ->when($request->payment_type, fn($q) => $q->where('payment_type', $request->payment_type))
        ->when($request->invoice_number, fn($q) => $q->where('invoice_number', 'like', "%{$request->invoice_number}%"))
        ->when($request->from_date, fn($q) => $q->whereDate('created_at', '>=', $request->from_date))
        ->when($request->to_date, fn($q) => $q->whereDate('created_at', '<=', $request->to_date))
        ->orderBy('id', 'desc')
        ->paginate(10);

    $clients = Client::all();

    return view('admin.sales.index', compact('sales', 'clients'));
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
            'admin.sales.create',
            compact('clients', 'safes', 'units', 'items', 'discountTypes', 'warehouses')
        );
    }

    public function store(SaleRequest $request)
    {
        DB::beginTransaction();
        //dd($request->all());
        $sale = auth()->user()->sales()->create($request->validated());// db
        $total = $this->attachItems($sale, $request);
        $this->updateSaleTotals($sale, $total, $request);

        // update safe & transaction
        (new SafeService)->inTransaction(
            $sale,
            $sale->paid_amount,
            'Sale Payment, Invoice #: ' . $sale->invoice_number);
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
        $balance = $sale->net_amount - $sale->paid_amount;
        if ($balance != 0) {
            // client account update
            $sale->client->increment('balance', $balance);
        }
        //-200 = 1000 - 1200
        $sale->clientAccountTransaction()->create([
            'user_id' => auth()->user()->id,
            'client_id' => $sale->client_id,
            'credit' => $sale->net_amount,
            'debit' => $sale->paid_amount,
            'balance' => $balance,
            'balance_after' => $sale->client->fresh()->balance,
            'description' => 'Sale Remaining Amount, Invoice #: ' . $sale->invoice_number,
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
            (new StockManageService())->decreaseStock($queriedItem, $request->warehouse_id, $item['qty'], $sale);
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



    public function show($id)
    {
        $sale = Sale::with(['client', 'safe', 'items', 'user'])->findOrFail($id);

        $items = $sale->items->map(function ($item) {
            return [
                'name' => $item->name,
                'quantity' => $item->pivot->quantity,
                'unit_price' => $item->pivot->unit_price,
                'total_price' => $item->pivot->total_price,
                'notes' => $item->pivot->notes,
            ];
        });

        return view('admin.sales.show', compact('sale', 'items'));
    }

    public function downloadPdf($id)
    {
        $sale = Sale::with(['client', 'safe', 'items', 'user'])->findOrFail($id);

        $items = $sale->items->map(function ($item) {
            return [
                'name' => $item->name,
                'quantity' => $item->pivot->quantity,
                'unit_price' => $item->pivot->unit_price,
                'total_price' => $item->pivot->total_price,
                'notes' => $item->pivot->notes,
            ];
        });

        $pdf = Pdf::loadView('admin.sales.pdf', compact('sale', 'items'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice_' . $sale->invoice_number . '.pdf');
    }

}
