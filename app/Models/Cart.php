<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'client_id',
        'status'
    ];

    // cart belongs to user
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // cart has many items
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

}

