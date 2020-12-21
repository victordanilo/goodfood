<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasUuid, SoftDeletes, HasApiTokens, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'cpf_cnpj',
        'phone',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addresses()
    {
        return $this->hasMany('App\CustomerAddress', 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Order', 'customer_uuid', 'uuid');
    }

    public function payments()
    {
        return $this->hasMany('App\Order', 'customer_uuid', 'uuid');
    }

    public function favoriteCompanies()
    {
        return $this->hasMany('App\FavoriteCompany', 'customer_id', 'id');
    }

    public function favoriteProducts()
    {
        return $this->hasMany('App\FavoriteProduct', 'customer_id', 'id');
    }
}
