<?php

namespace App;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class Product extends Model
{
    use HasUuid, SoftDeletes, Searchable;

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
        'price',
        'stock',
        'image',
        'company_uuid',
        'category_uuid',
    ];

    public function addStock(int $qty)
    {
        $this->stock += $qty;
        $this->save();

        return $this->stock;
    }

    public function removeStock(int $qty)
    {
        if ($this->stock < $qty) {
            return false;
        }

        $this->stock -= $qty;
        $this->save();

        return $this->stock;
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_uuid', 'uuid');
    }

    public function category()
    {
        return $this->belongsTo('App\ProductCategory', 'category_uuid', 'uuid');
    }

    public function reviews()
    {
        return $this->hasMany('App\ProductReview', 'product_uuid', 'uuid');
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();
        $extraFields = [
            'category' => $this->category,
        ];

        return array_merge($array, $extraFields);
    }
}
