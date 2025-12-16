<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatusEnum;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'status',
        'payment_method',
        'price',
        'shipping_cost',
        'total_price',
        'sale_id',
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class,
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isDelivered(): bool
    {
        return $this->status === OrderStatusEnum::DELIVERED;
    }
}
