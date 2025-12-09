<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'status'
    ];

    // cart belongs to user
    public function clients()
    {
        return $this->belongsTo(Client::class);
    }

    // cart has many items
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}

