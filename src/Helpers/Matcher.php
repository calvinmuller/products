<?php

namespace Istreet\Products\Helpers;

use Istreet\Products\Spree;
use Istreet\Products\Superbalist;
use Istreet\Products\Takealot;
use Istreet\Products\Woolworths;
use Superbalist\Product;

class Matcher
{

    /**
     * Plugins
     * @var array
     */
    protected $plugins = [
        // https://superbalist.com/women/shoes/sneakers/authentic-vee3bkl-black-white/52585?ref=department_152%2Fcategory_215%2Fcategory_218
        Superbalist::class => '/superbalist.com|[0-9]{4,6}/',
        // https://www.takealot.com/paklite-altitude-61cm-trolley-case-spinner-navy/PLID42456113
        Takealot::class => '/www.takealot.com|PLID[0-9]{6,8}/',
        // https://www.spree.co.za/c-inch-mesh-inset-top-black/product/13ZEEA7?ref_catid=280
        Spree::class => '/www.spree.co.za|[A-Z|0-9]{6,8}/',
        // http://www.woolworths.co.za/store/prod/Men/New-In/Clothing/Stripe-Tricot-Track-Pants/_/A-504075712
        Woolworths::class => '/www.woolworths.co.za|[A-Z|0-9]{6,10}/',
    ];


    /**
     *
     * @param $url
     */
    public function match($url, $raw = false)
    {

        foreach ($this->plugins as $plugin => $value) {

            $matches = [];

            $match = preg_match_all($value, $url, $matches);

            if ($match && count($matches[0]) > 1) {
                return app()->make($plugin)->find($matches[0][1], $raw);
            }
        }
    }


    /**
     * Decide on the ID
     */
    public function decider()
    {

    }
}
