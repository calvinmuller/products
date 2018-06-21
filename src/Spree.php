<?php

namespace Istreet\Products;


use Carbon\Carbon;
use Illuminate\Support\Str;
use Spree\Api;

class Spree extends Api
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Meh
     * @param $id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function find($id, $raw = false)
    {

        $product = Product::findOrCreate($id, \Spree\Product::class);

        $item = json_decode($this->get("catalog/product/{$id}")->getBody());

        if ($raw) {
            return response()->json($item);
        }

        $data = [
            'external_id' => $id,
            'name' => $item->title,
            'reference_id' => $item->entity_id,
            'category_name' => $item->department->category_name,
            'brand' => object_get($item, 'brand.name'),
            'class' => \Spree\Product::class,
            'description' => $item->detail,

            'price' => ($item->price->special_price) ? $item->price->special_price * 100 : $item->price->selling * 100,
            'retail_price' => $item->price->regular * 100,
            'old_selling_price' => object_get($item, 'price.special_price'),

            'data' => object_get($item, 'meta', []),
            'saving' => object_get($item, 'price.discount_percentage'),
            'is_on_special' => object_get($item, 'price.is_discounted'),
            'stock_on_hand' => collect($item->simples)->sum('stock_qty'),

            'date_released' => Carbon::parse(object_get($item, 'go_live'))->format('Y-m-d H:i:s'),
        ];

        $product->fill($data)->save();

        $this->processAssets($product, $item->pics);
        $this->processVariations($product, $item->simples);


        return $product;
    }


    /**
     * @param Product $product
     * @param $assets
     */
    private function processAssets(Product $product, $assets)
    {
        $prefix = 'https://www.spree.co.za/imgop/w-pdp-600x803/media/catalog/product';

        array_push($assets->gallery, $assets->small);
        array_push($assets->gallery, $assets->hover);

        foreach ($assets->gallery as $item) {
            $product->assets()->updateOrCreate([
                'full_size' => $prefix . $item
            ], [
                'extension' => 'jpg',
                'full_size' => $prefix . $item,
                'url' => $prefix . $item,
                'thumb' => $prefix . $item,
                'tiny' => $prefix . $item,
            ]);
        }

    }


    /**
     * @param Product $product
     * @param $variations
     */
    private function processVariations(Product $product, $variations)
    {
        foreach ($variations as $item) {
            $product->variations()->updateOrCreate([
                'sku_id' => $item->entity_id
            ], [
                'quantity' => $item->stock_qty,
                'sku' => $item->sku,
                'price' => $product->price,
                'status' => ($item->stock_status) ? 'Available' : 'Out of stock',
                'name' => object_get($item, 'size_value')
            ]);
        }
    }


    /**
     * @param $brand
     * @return \Illuminate\Database\Eloquent\Model|Brand
     */
    public function processBrand($item)
    {

        $brand = Brand::findOrCreate($item->name, [
            'reference_id' => $item->id,
            'name' => $item->name,
            'slug' => Str::slug($item->name),
        ]);

        $brand->save();

        $brand->asset()->updateOrCreate([
            'full_size' => $item->image
        ], [
            'extension' => '',
            'full_size' => $item->image,
            'url' => $item->image,
            'thumb' => $item->image,
            'tiny' => $item->image,
        ]);

        return $brand;

    }
}
