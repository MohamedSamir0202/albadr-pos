<?php

namespace App\Models;

use App\Enums\WarehouseStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses';
    public $timestamps = true;
    protected $fillable = array('name', 'description', 'status');

    protected $casts = [
        'status' => WarehouseStatusEnum::class,
    ];


    public function items()
    {
        return $this->morphToMany('App\Models\Item', 'itemable')
            ->withPivot('quantity');
    }

    public function image()
{
    return $this->morphOne(\App\Models\File::class, 'fileable')
        ->where('usage', 'warehouse_photo');
}


}
