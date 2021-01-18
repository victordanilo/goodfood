<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MercadoPagoCredential extends Model
{
    protected $primaryKey = 'company_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mp_user_id',
        'public_key',
        'access_token',
        'refresh_token',
        'expires_in',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id', 'id');
    }
}
