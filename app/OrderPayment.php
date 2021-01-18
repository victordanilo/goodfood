<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class OrderPayment extends Model
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
        'amount',
        'mp_payment_id',
        'order_uuid',
        'customer_uuid',
        'method_id',
        'status_id',
    ];

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_uuid', 'uuid');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_uuid', 'uuid');
    }

    public function method()
    {
        return $this->belongsTo('App\PaymentMethod', 'method_id');
    }

    public function status()
    {
        return $this->belongsTo('App\PaymentStatus', 'status_id');
    }
}
