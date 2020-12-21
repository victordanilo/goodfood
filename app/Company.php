<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable
{
    use HasUuid, SoftDeletes, HasApiTokens, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'trade',
        'cpf_cnpj',
        'owner',
        'phone',
        'email',
        'password',
        'delivery_cost',
        'avatar',
        'plan_uuid',
        'level',
        'lat',
        'lng',
        'street',
        'n',
        'complement',
        'district',
        'zip_code',
        'city',
        'uf',
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

    public function credential()
    {
        return $this->hasOne('App\MercadoPagoCredential', 'company_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo('App\Plan', 'plan_uuid');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'company_uuid', 'uuid');
    }

    public function orders()
    {
        return $this->hasMany('App\Order', 'company_uuid', 'uuid');
    }
}
