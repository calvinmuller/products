<?php

namespace Istreet\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    //
    use Searchable, \OwenIt\Auditing\Auditable;

    protected $guarded = ['id'];

    protected $fillable = [];

    protected $casts = [
        'data' => 'array',
        'price' => 'float',
        'retail_price' => 'float'
    ];

    protected $hidden = [
        'class',
//        'brand'
    ];

    protected $with = [
        'assets',
//        'variations',
        'designer',
        'supplier'
    ];

    protected $appends = [
        'formatted_price',
        'formatted_retail_price'
    ];


    protected $auditExclude = [
        'is_on_special',
        'data'
    ];

    public function searchableAs()
    {
        return 'products_index';
    }

    public function designer()
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
     * Update name attribute
     * @param $value
     */
    public function setNameAttribute($value) {
        $this->attributes['slug'] = Str::slug($value);
        $this->attributes['name'] = $value;
    }


    public function supplier() {
        return $this->hasOne(Supplier::class, 'class', 'class');
    }


    /**
     * Remove the eB
     * @param $value
     */
    public function setEbucksAttribute($value)
    {
        $ebucks = str_replace(["eB", " "], ["", ""], $value);
        $this->attributes['ebucks'] = (int) $ebucks;
    }


    /**
     * Remove the %
     * @param $value
     */
    public function setSavingAttribute($value)
    {
        $saving = str_replace("%", "", $value);
        $this->attributes['saving'] = $saving;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function alerts()
    {
        return $this->hasManyThrough(Alert::class, Variation::class);
    }

    public function getPriceAttribute($value) {
//        return current
        return $value;
    }


    /**
     * @param $value
     * @return string
     */
    public function getFormattedPriceAttribute($value) {
        return number_format($this->price / 100, 2);
    }

    /**
     * @param $value
     * @return string
     */
    public function getFormattedRetailPriceAttribute() {
        return number_format($this->retail_price / 100, 2);
    }


    /**
     * {@inheritdoc}
     */
    public function transformAudit(array $data): array
    {
        $data['old_price'] = $this->getOriginal('price');
        $data['new_price'] = $this->getAttribute('price');

        $data['old_stock'] = $this->getOriginal('stock_on_hand');
        $data['new_stock'] = $this->getAttribute('stock_on_hand');

        return $data;
    }
}
