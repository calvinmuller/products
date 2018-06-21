<?php

namespace Istreet\Products;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    //

    protected $guarded = ['id'];


    protected $casts = [
        'price' => 'float'
    ];
}
