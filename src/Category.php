<?php

namespace Istreet\Products;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Category extends Model
{
    //
    use Searchable;

    protected $with = [
//        'categories'
    ];

    protected $hidden = [
        'url',
        'class'
    ];

    protected $guarded = ['id'];


    public static function findOrCreate($id)
    {
        $obj = self::where('title', '=', $id)
            ->first();

        return $obj ?: new static();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(__CLASS__, 'category_id');
    }


    public function searchableAs()
    {
        return 'categories_index';
    }

    /**
     * Related categories
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
//    public function categories()
//    {
//        return $this->hasMany(Category::class, 'category_id', 'id');
//    }
}
