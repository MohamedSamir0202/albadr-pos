<?php

namespace App\Models;

use App\Enums\ClientRegistrationEnum;
use App\Enums\ClientStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'email', 'phone', 'address', 'balance', 'status', 'registered_via');

    protected $casts = [
        'registered_via' => ClientRegistrationEnum::class,
        'status' => ClientStatusEnum::class,
    ];

    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }

    public function accountTransactions(): HasMany|Client
    {
        return $this->hasMany('App\Models\ClientAccountTransaction');
    }


}
