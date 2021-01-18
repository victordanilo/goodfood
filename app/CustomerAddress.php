<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class CustomerAddress extends Model
{
    use HasUuid;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'customer_id',
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

    protected $hidden = [
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
}
