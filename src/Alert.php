<?php

namespace Istreet\Products;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alert extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'user_id',
        'variation',
        'info',
        'preferred_method'
    ];

    protected $dates = ['deleted_at'];

    /**
     * An alert belongs to a user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }


    /**
     * An alert belongs to a product
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo(Product::class);
    }

}
