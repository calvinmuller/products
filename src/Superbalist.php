<?php

namespace Istreet\Products;


use Istreet\Products\Interfaces\ProductLoader;
use Superbalist\Product as SProduct;

class Superbalist implements ProductLoader
{

    private $api;

    public function __construct(SProduct $api)
    {
        $this->api = $api;
    }

    /**
     * Meh
     * @param $productId
     * @param array $parameters
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function find($id, $raw = false)
    {

        $product = Product::findOrCreate($id, self::class);

        $item = json_decode($this->api->find($id)->getBody());

        if ($raw) {
            return response()->json($item);
        }

        $data = [
            'name' =>  $item->page_impression->payload->page->title,
            'reference_id' => $item->id,
            'brand' => $item->designer,
            'class' => self::class,
            'price' => ($item->special_price) ? $item->special_price * 100 : $item->price * 100,
            'retail_price' => $item->retail_price * 100,
            'old_selling_price' => $item->retail_price * 100,
            'data' => $item->data,
            'external_id' => $id,
            'category_name' => object_get($item, 'category_name'),
            'description' => $item->description,
            'html_description' => $item->html_description,
            'saving' => object_get($item, 'percentage_reduced'),
            'is_on_special' => $item->special_price ? true : false,
            'stock_on_hand' => object_get($item, 'stock_quantity'),
            'date_released' => object_get($item, 'date_released'),
            'status' => $item->status,
            'variation_name' => $item->variation_name,
            'sku' => $item->data->{"Style Code"}
        ];

        $product->fill($data)->save();

        $this->processAssets($product, $item->assets);
        $this->processVariations($product, $item->variations);

        $product->load(['assets', 'variations']);

        return $product;
    }


    /**
     * @param Product $product
     * @param $assets
     */
    public function processAssets(Product $product, $assets)
    {
        foreach ($assets as $item) {
            $product->assets()->updateOrCreate([
                'full_size' => $item->full_size
            ], [
                'extension' => $item->extension,
                'full_size' => $item->full_size,
                'url' => $item->url,
                'thumb' => $item->thumb,
                'tiny' => $item->tiny,
            ]);
        }

    }


    /**
     * @param Product $product
     * @param $variations
     */
    public function processVariations(Product $product, $variations)
    {
        foreach ($variations as $item) {
            $product->variations()->updateOrCreate([
                'sku_id' => $item->sku_id
            ], [
                'quantity' => $item->quantity,
                'sku_id' => $item->sku_id,
                'sku' => $item->sku_id,
                'price' => ($item->special_price) ? $item->special_price * 100 : $item->price * 100,
                'status' => $item->status,
                'name' => $item->name,
            ]);
        }
    }


    public function processBrand(Product $product, $brand)
    {
        // TODO: Implement processBrand() method.
    }
}
