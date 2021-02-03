<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class OrderDetail extends Model
{
    use HasUuid;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_uuid',
        'product_uuid',
        'qty',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_uuid', 'uuid');
    }

    public function products()
    {
        return $this->belongsTo('App\Product', 'product_uuid');
    }
}
