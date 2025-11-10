<?php

namespace App\Services;

use App\Enums\WarehouseTransactionTypeEnum;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WarehouseTransactionService
{
    
    public static function initStock(Item $item, Warehouse $warehouse, float $quantity): void
    {
        DB::transaction(function () use ($item, $warehouse, $quantity) {
            $item->warehouses()->attach($warehouse->id, ['quantity' => $quantity]);

            $item->warehouseTransactions()->create([
                'transaction_type' => WarehouseTransactionTypeEnum::init,
                'quantity' => $quantity,
                'quantity_after' => $quantity,
                'reference_type' => null,
                'reference_id' => null,
                'description' => 'Initial stock for warehouse: ' . $warehouse->name,
            ]);
        });
    }


    public static function increase(Item $item, Warehouse $warehouse, float $quantity, ?Model $reference = null, ?string $description = null): void
    {
        DB::transaction(function () use ($item, $warehouse, $quantity, $reference, $description) {
            $pivot = $item->warehouses()->where('itemable_id', $warehouse->id)->first();

            if (!$pivot) {
                self::initStock($item, $warehouse, 0);
            }

            $item->warehouses()->where('itemable_id', $warehouse->id)->increment('quantity', $quantity);

            $newQty = $item->warehouses()->where('itemable_id', $warehouse->id)->first()->pivot->quantity;

            $item->warehouseTransactions()->create([
                'transaction_type' => WarehouseTransactionTypeEnum::add,
                'quantity' => $quantity,
                'quantity_after' => $newQty,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->id,
                'description' => $description ?? 'Stock increased in warehouse: ' . $warehouse->name,
            ]);
        });
    }


    public static function decrease(Item $item, Warehouse $warehouse, float $quantity, ?Model $reference = null, ?string $description = null): void
    {
        DB::transaction(function () use ($item, $warehouse, $quantity, $reference, $description) {
            $pivot = $item->warehouses()->where('itemable_id', $warehouse->id)->first();

            if (!$pivot) {
                self::initStock($item, $warehouse, 0);
            }

            $item->warehouses()->where('itemable_id', $warehouse->id)->decrement('quantity', $quantity);

            $newQty = $item->warehouses()->where('itemable_id', $warehouse->id)->first()->pivot->quantity;

            $item->warehouseTransactions()->create([
                'transaction_type' => WarehouseTransactionTypeEnum::sub,
                'quantity' => -$quantity,
                'quantity_after' => $newQty,
                'reference_type' => $reference?->getMorphClass(),
                'reference_id' => $reference?->id,
                'description' => $description ?? 'Stock decreased in warehouse: ' . $warehouse->name,
            ]);
        });
    }


    public static function adjust(Item $item, Warehouse $warehouse, float $newQuantity, ?string $reason = null): void
    {
        DB::transaction(function () use ($item, $warehouse, $newQuantity, $reason) {
            $pivot = $item->warehouses()->where('itemable_id', $warehouse->id)->first();

            if (!$pivot) {
                self::initStock($item, $warehouse, $newQuantity);
                return;
            }

            $item->warehouses()->updateExistingPivot($warehouse->id, ['quantity' => $newQuantity]);

            $item->warehouseTransactions()->create([
                'transaction_type' => WarehouseTransactionTypeEnum::adjust,
                'quantity' => $newQuantity,
                'quantity_after' => $newQuantity,
                'reference_type' => null,
                'reference_id' => null,
                'description' => $reason ?? 'Manual stock adjustment in warehouse: ' . $warehouse->name,
            ]);
        });
    }
}
