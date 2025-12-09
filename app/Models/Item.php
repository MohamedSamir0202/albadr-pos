<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items';
    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'item_code',
        'description',
        'price',
        'quantity',
        'is_shown_in_store',
        'minimum_stock',
        'status',
        'category_id',
        'unit_id',
    ];

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function mainPhoto()
    {
        return $this->morphOne('App\Models\File', 'fileable')->where('usage', 'item_photo');
    }

    public function gallery()
    {
        return $this->morphMany('App\Models\File', 'fileable')->where('usage', 'item_gallery');
    }

    public function sales()
    {
        return $this->morphedByMany('App\Models\Sale', 'itemable');
    }

    public function returns()
    {
        return $this->morphedByMany('App\Models\SaleReturn', 'itemable');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order', 'item_orders')
            ->withPivot('unit_price', 'quantity', 'total_price');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function image()
    {
        return $this->morphOne('App\Models\File', 'fileable')
            ->where('usage', 'item_photo');
    }

    public function warehouses()
    {
        return $this->morphedByMany('App\Models\Warehouse', 'itemable')
            ->withPivot('quantity');
    }

    public function warehouseTransactions()
    {
        return $this->hasMany('App\Models\WarehouseTransaction');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

}
