<?php

namespace Istreet\Products;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    //
    use Searchable;

    protected $guarded = ['id'];

    protected $fillable = [];

    protected $casts = [
        'data' => 'array',
        'price' => 'float',
        'retail_price' => 'float'
    ];

    protected $with = [
//        'assets',
//        'variations',
        'brand'
    ];

    public function searchableAs()
    {
        return 'products_index';
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'name', 'brand');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function assets()
    {
        return $this->morphMany(Asset::class, 'assetable');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations()
    {
        return $this->hasMany(Variation::class);
    }


    public static function findOrCreate($id, $class = \TakealotApi\Product::class)
    {
        $obj = self::where('external_id', '=', $id)
            ->where('class', '=', $class)
            ->first();

        return $obj ?: new static();
    }


    /**
     * Remove the eB
     * @param $value
     */
    public function setEbucksAttribute($value)
    {
        $ebucks = str_replace("eB", "", $value);
        $this->attributes['ebucks'] = $ebucks;
    }
}
