<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatus extends Model
{
    use SoftDeletes;

    protected $table = 'order_status';

    const PENDING = 1;
    const APPROVED = 2;
    const COMPLETE = 3;
    const RETURNED = 4;
    const CANCELED = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
