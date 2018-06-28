<?php

namespace Istreet\Products\Helpers;

use Istreet\Products\Spree;
use Istreet\Products\Superbalist;
use Istreet\Products\Supplier;
use Istreet\Products\Suppliers\Zando;
use Istreet\Products\Takealot;
use Istreet\Products\Woolworths;

class Matcher
{

    protected $suppliers;

    public function __construct(Supplier $suppliers)
    {
        $this->suppliers = $suppliers;
    }

    /**
     *
     * @param $url
     */
    public function match($url, $raw = false)
    {

        foreach ($this->suppliers->all() as $supplier) {

            $matches = [];

            $match = preg_match_all($supplier->identifier, $url, $matches);

            if ($match && count($matches[0]) > 1) {
                return app()->make($supplier->class)->find($matches[0][1], $raw);
            }
        }

        abort(404, trans('supplier.not_found'));
    }


    /**
     * Decide on the ID
     */
    public function decider()
    {

    }
}
