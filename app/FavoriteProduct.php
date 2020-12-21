<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteProduct extends Model
{
    protected $primaryKey = ['product_uuid', 'customer_id'];
    public $incrementing = false;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_uuid',
        'customer_id',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
}
