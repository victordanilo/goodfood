<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class PlanPayment extends Model
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
        'mp_payment_id',
        'amount',
        'plan_uuid',
        'company_uuid',
        'method_id',
        'status_id',
    ];

    public function plan()
    {
        return $this->belongsTo('App\Plan', 'plan_uuid', 'uuid');
    }
}
