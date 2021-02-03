<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteCompany extends Model
{
    protected $primaryKey = ['company_id', 'customer_id'];
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id', 'id');
    }
}
