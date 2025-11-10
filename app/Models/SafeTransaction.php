<?php

namespace App\Models;

use App\Enums\SafeTransactionTypeEnum;
use Illuminate\Database\Eloquent\Model;

class SafeTransaction extends Model
{

    protected $table = 'safe_transactions';
    public $timestamps = true;
    protected $fillable = array('type', 'amount', 'description', 'balance_after', 'safe_id', 'user_id');

    protected $casts = [
        'type' => SafeTransactionTypeEnum::class
    ];

    public function safe()
    {
        return $this->belongsTo('App\Models\Safe');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reference()
    {
        return $this->morphTo();
    }

}
