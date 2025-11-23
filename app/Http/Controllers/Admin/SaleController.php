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
use App\Settings\AdvancedSettings;
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

    public function create(AdvancedSettings $advancedSettings)
    {
        $clients = Client::all();
        $safes = Safe::where('status', SafeStatusEnum::active)->get();
        $units = Unit::where('status', UnitStatusEnum::active)->get();
        $items = Item::where('status', ItemStatusEnum::active)->get();
        $warehouses = Warehouse::all();
        $discountTypes = DiscountTypeEnum::labels();

        return view('admin.sales.create', compact(
            'clients',
            'safes',
            'units',
            'items',
            'discountTypes',
            'warehouses',
            'advancedSettings'
        ));
    }

    public function store(SaleRequest $request)
    {
        DB::beginTransaction();

        try {

            $sale = auth()->user()->sales()->create($request->validated());


            $total = $this->attachItems($sale, $request);


            $this->updateSaleTotals($sale, $total, $request);


            (new SafeService)->inTransaction(
                $sale,
                $sale->paid_amount,
                'Sale Payment, Invoice #: ' . $sale->invoice_number
            );


            $this->updateClientAccountBalance($sale);

            DB::commit();

            return back()->with('success', 'Sale created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    private function attachItems(Sale $sale, SaleRequest $request): float
    {
        $total = 0;

        foreach ($request->items as $itemData) {
            $item = Item::findOrFail($itemData['id']);
            $totalPrice = $itemData['qty'] * ($itemData['price'] ?? $item->price);

            $sale->items()->attach($item->id, [
                'unit_price' => $itemData['price'] ?? $item->price,
                'quantity' => $itemData['qty'],
                'total_price' => $totalPrice,
                'notes' => $itemData['notes'] ?? null,
            ]);


            (new StockManageService())->decreaseStock(
                $item,
                $request->warehouse_id,
                $itemData['qty'],
                $sale
            );

            $total += $totalPrice;
        }

        return $total;
    }


    private function calculateDiscount(SaleRequest $request, float|int $total): float
    {
        $discountValue = $request->discount_value ?? 0;

        if ($request->discount_type == DiscountTypeEnum::percentage->value) {
            return $total * ($discountValue / 100);
        }

        return $discountValue;
    }


    private function updateSaleTotals(Sale $sale, float|int $total, SaleRequest $request): void
    {
        $discount = $this->calculateDiscount($request, $total);
        $net = $total - $discount;

        $paid = $request->payment_type == PaymentTypeEnum::debt->value
            ? $request->payment_amount
            : $net;

        $remaining = $net - $paid;

        $sale->update([
            'total' => $total,
            'discount' => $discount,
            'net_amount' => $net,
            'paid_amount' => $paid,
            'remaining_amount' => $remaining,
        ]);
    }


    private function updateClientAccountBalance(Sale $sale): void
    {
        $balance = $sale->net_amount - $sale->paid_amount;

        if ($balance != 0) {
            $sale->client->increment('balance', $balance);
        }

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

    public function payRemaining(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $amount = $request->amount;

        if ($amount > $sale->remaining_amount) {
            return back()->withErrors(['amount' => 'Amount exceeds remaining balance.']);
        }

        DB::beginTransaction();


        (new SafeService)->inTransaction(
            $sale,
            $amount,
            $request->description ?? 'Remaining payment for Invoice #' . $sale->invoice_number
        );


        $sale->client->decrement('balance', $amount);

        $sale->paid_amount += $amount;
        $sale->remaining_amount -= $amount;
        $sale->save();

        DB::commit();

        return back()->with('success', 'Remaining amount has been paid successfully.');
    }
}
