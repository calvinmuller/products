<?php

namespace Istreet\Products;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Variation extends Model implements Auditable
{
    //
    use \OwenIt\Auditing\Auditable;

    protected $guarded = ['id'];


    protected $casts = [
        'price' => 'float'
    ];


    /**
     * Try add alerts onto variations
     */
    public function alert()
    {
        return $this->hasOne(Alert::class);
    }


    /**
     * {@inheritdoc}
     */
    public function transformAudit(array $data): array
    {
        $data['old_price'] = $this->getOriginal('price');
        $data['new_price'] = $this->getAttribute('price');

        $data['old_stock'] = $this->getOriginal('quantity');
        $data['new_stock'] = $this->getAttribute('quantity');

        return $data;
    }
}
