<?php
/**
 * Created by IntelliJ IDEA.
 * User: calvinmuller
 * Date: 2018/06/21
 * Time: 14:55
 */

namespace Istreet\Products\Interfaces;


use Istreet\Products\Product;

interface ProductLoader
{

    public function find($id, $raw);

    public function processAssets(Product $product, $assets);

    public function processVariations(Product $product, $variations);

    public function processBrand(Product $product, $brand);
}
