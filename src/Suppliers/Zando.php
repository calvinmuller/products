<?php

namespace Istreet\Products\Suppliers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Istreet\Products\Interfaces\ProductLoader;
use Istreet\Products\Product;
use Istreet\Products\Suppliers\Zando\Api;

class Zando extends Api implements ProductLoader {

    /**
     * Zando constructor.
     */


    public function find($id, $raw)
    {
        // TODO: Implement find() method.

        $item = $this->_search($id);

        if ($raw) {
            return response()->json($id);
        }

        $product = Product::findOrCreate($id, Zando::class);

        $price = $item->price_data->tracking->price;
        $retail_price = $item->price_data->tracking->retail_price;

        $ref_id = explode("-", collect($item->simples)->first()->sku);

        $data = [
            'name' => $item->heading,
            'sku' => $item->sku,
            'reference_id' => $ref_id[1],
            'brand' => $item->brand_name,
            'class' => Zando::class,
            'price' => (int) $price * 100,
            'retail_price' => (int) $retail_price * 100,
            'old_selling_price' => (int) $retail_price * 100,
            'discovery' => 0,
            'ebucks' => $item->price_data->ebucks_price,
            'data' => $item->data ?? [],
            'external_id' => $id,
            'category_name' => object_get($item, 'category_name'),
            'description' => $item->description,
            'saving' => object_get($item, 'price_data.saving_amount', 0),
            'is_on_special' => $item->price_data->sale,
            'stock_on_hand' => collect($item->simples)->sum('stock'),
            'date_released' => Carbon::now()->format('Y-m-d H:i:s'),
            'status' => '',
        ];

        $product->fill($data)->save();

        $this->processAssets($product, $item->images);
        $this->processVariations($product, $item->simples);

        $product->load(['assets', 'variations']);

        return $product;

    }

    /**
     * Zando makes it difficult for us
     * @param $name
     */
    private function _search($name) {

        $id = json_decode($this->get('api/catalog/', [
            'query' => [ 'q' => $name ]
        ])->getBody())->items[0]->sku;

        return json_decode($this->get('api/catalog/product/', [
            'query' => [ 'sku' => $id ]
        ])->getBody());

    }

    public function processAssets(Product $product, $assets)
    {
        // TODO: Implement processAssets() method.

        foreach ($assets as $item) {

            $product->assets()->updateOrCreate([
                'full_size' => str_replace("400x", "1024x", $item)
            ], [
                'extension' => 'jpg',
                'full_size' => str_replace("400x", "1024x", $item),
                'url' => $item,
                'thumb' => $item,
                'tiny' => $item,
            ]);
        }

    }

    public function processVariations(Product $product, $variations)
    {
        // TODO: Implement processVariations() method.

        foreach ($variations as $item) {

            $skuId = explode("-", $item->sku);

            $price = $item->price_data->tracking->price;
            $retail_price = $item->price_data->tracking->retail_price;

            $product->variations()->updateOrCreate([
                'sku_id' => $skuId[1]
            ], [
                'quantity' => $item->stock,
                'sku_id' => $skuId[1],
                'sku' => $item->sku,
                'price' => $price * 100,
                'retail_price' => $retail_price * 100,
                'status' => ($item->stock > 0) ? 'Avaialable': 'Out of Stock' ,
                'name' => $item->size,
            ]);
        }

    }

    public function processBrand(Product $product, $brand)
    {
        // TODO: Implement processBrand() method.
    }
}
