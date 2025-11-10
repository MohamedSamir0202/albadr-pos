<?php

namespace App\Models;

use App\Enums\SafeStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Safe extends Model
{

    protected $table = 'safes';
    public $timestamps = true;
    protected $fillable = array('name', 'balance', 'status', 'description', 'type');

    protected $casts = [
        'status' => SafeStatusEnum::class,
    ];
    public function transactions()
    {
        return $this->hasMany(SafeTransaction::class);
    }

}
