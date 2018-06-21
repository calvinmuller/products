<?php

namespace Istreet\Products;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    //

    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        'assetable_type', 'assetable_id'
    ];

    /**
     * Get all of the owning addressable models.
     */
    public function assetable()
    {
        return $this->morphTo();
    }
}
