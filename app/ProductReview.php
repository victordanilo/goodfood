<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class ProductReview extends Model
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
        'review',
        'rate',
        'product_uuid',
        'customer_uuid',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_uuid', 'uuid');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_uuid', 'uuid');
    }
}
