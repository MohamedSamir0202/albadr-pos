<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\WarehouseTransactionTypeEnum;

class WarehouseTransaction extends Model
{
    protected $fillable = [
        'warehouse_id',
        'item_id',
        'transaction_type',
        'reference_id',
        'reference_type',
        'user_id',
        'quantity',
        'quantity_after',
        'description',
    ];

    protected $casts = [
        'transaction_type' => WarehouseTransactionTypeEnum::class,
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
