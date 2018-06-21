<?php

namespace Istreet\Products;


use Illuminate\Support\Str;
use TakealotApi\Api;

class Takealot extends Api
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

        $product = Product::findOrCreate($id);

        $item = json_decode($this->get("productline/$id")
            ->getBody())->response;

        if ($raw) {
            return response()->json($item);
        }

        $this->processBrand($item->brand);

        $ref = object_get($item, 'skus')[0]->id;

        $data = [
            'external_id' => $id,
            'name' => $item->title,
            'reference_id' => $ref,
            'category_name' => $item->categories[0]->name,
            'brand' => object_get($item, 'brand.name'),
            'class' => \TakealotApi\Product::class,
            'description' => $item->description,
            'price' => $item->selling_price,
            'retail_price' => $item->old_selling_price,
            'data' => object_get($item, 'meta'),
            'old_selling_price' => object_get($item, 'old_selling_price'),
            'saving' => object_get($item, 'saving'),
            'is_on_special' => object_get($item, 'is_on_special'),
            'stock_on_hand' => object_get($item, 'stock_on_hand'),
            'date_released' => object_get($item, 'date_released'),
        ];

        $product->fill($data)->save();

        $this->processAssets($product, $item->images);
        $this->processVariations($product, $item->skus);

        $product->load(['assets', 'variations', 'brand']);

        return $product;
    }


    /**
     * @param Product $product
     * @param $assets
     */
    private function processAssets(Product $product, $assets)
    {
        foreach ($assets as $item) {
            $product->assets()->updateOrCreate([
                'full_size' => $item->full
            ], [
                'extension' => 'jpg',
                'full_size' => $item->full,
                'url' => $item->full,
                'thumb' => $item->small,
                'tiny' => $item->small,
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
                'sku_id' => $item->id
            ], [
                'quantity' => $item->stock_on_hand,
                'sku_id' => $item->id,
                'sku' => $item->id,
                'price' => $item->selling_price,
                'status' => ($item->is_available) ? 'Available' : 'Out of stock',
                'name' => object_get($item, 'size.value')
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
