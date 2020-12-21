<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Plan extends Model
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
        'description',
        'slug',
        'price',
        'rate',
        'commission',
    ];

    public function companies()
    {
        return $this->hasMany('App\Company', 'plan_uuid', 'uuid');
    }

    public function payment()
    {
        return $this->hasOne('App\PlanPayment', 'plan_uuid', 'uuid');
    }
}
