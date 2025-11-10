<?php

namespace App\Services;

use App\Enums\WarehouseTransactionTypeEnum;

class StockManageService
{
    public function initStock($item, $warehouseId, $initialStock)
    {

        $item->warehouses()->attach($warehouseId, [
            'quantity' => $initialStock
        ]);


        $item->warehouseTransactions()->create([
            'transaction_type' => WarehouseTransactionTypeEnum::init,
            'quantity'         => $initialStock,
            'quantity_after'   => $initialStock,
            'description'      => 'Initial stock added to warehouse ID: ' . $warehouseId,
            'warehouse_id'     => $warehouseId,
            'item_id'          => $item->id,
        ]);
    }


    public function decreaseStock($item, $warehouseId, $quantity, $reference = null)
    {

        $pivot = $item->warehouses()->where('warehouses.id', $warehouseId)->first();


        if (!$pivot) {
            $this->initStock($item, $warehouseId, 0);
            $pivot = $item->warehouses()->where('warehouses.id', $warehouseId)->first();
        }


        $item->warehouses()
            ->updateExistingPivot($warehouseId, [
                'quantity' => $pivot->pivot->quantity - $quantity
            ]);


        $newQty = $pivot->pivot->quantity - $quantity;


        $item->warehouseTransactions()->create([
            'transaction_type' => WarehouseTransactionTypeEnum::sub,
            'quantity'         => -$quantity,
            'quantity_after'   => $newQty,
            'description'      => 'Stock decreased from warehouse ID: ' . $warehouseId
                                . ($reference ? ', Reference ID: '.$reference->id : ''),
            'warehouse_id'     => $warehouseId,
            'item_id'          => $item->id,
            'reference_id'     => $reference?->id,
            'reference_type'   => $reference ? get_class($reference) : null,
        ]);
    }
}
