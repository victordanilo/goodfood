<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Order extends Model
{
    use HasUuid, SoftDeletes;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_uuid',
        'customer_uuid',
        'amount',
        'delivery_price',
        'delivery_address',
        'status_id',
        'obs',
    ];

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_uuid', 'uuid');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_uuid', 'uuid');
    }

    public function payment()
    {
        return $this->hasOne('App\OrderPayment', 'order_uuid', 'uuid');
    }

    public function status()
    {
        return $this->belongsTo('App\OrderStatus', 'status_id');
    }

    public function items()
    {
        return $this->hasMany('App\OrderDetail', 'order_uuid', 'uuid');
    }
}
