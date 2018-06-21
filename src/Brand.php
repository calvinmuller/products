<?php

namespace Istreet\Products;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Brand extends Model
{
    //
    use Searchable;

    protected $guarded = ['id'];

    protected $with = [
        'asset'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function asset()
    {
        return $this->morphOne(Asset::class, 'assetable');
    }


    public static function find($name) {
        $obj = self::where('name', '=', $name)
            ->first();

        return $obj;
    }

    /**
     * @param $id
     * @param $attributes
     * @return $this|Model
     */
    public static function findOrCreate($id, $attributes)
    {
        $obj = self::where('name', '=', $id)
            ->first();

        return $obj ? $obj->fill($attributes) : static::create($attributes);
    }
}
