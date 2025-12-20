<?php

namespace App\Models;

use App\Enums\ClientStatusEnum;
use App\Enums\ClientRegistrationEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\Contracts\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'clients';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'email', 'phone', 'address', 'balance',
     'status', 'registered_via' , 'password');

    protected $casts = [
        'registered_via' => ClientRegistrationEnum::class,
        'status' => ClientStatusEnum::class,
    ];
    protected $hidden = [
        'password',
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
