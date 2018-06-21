<?php
/**
 * Created by IntelliJ IDEA.
 * User: calvinmuller
 * Date: 2018/06/21
 * Time: 14:42
 */

namespace Istreet\Products;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Istreet\Products\Interfaces\ProductLoader;

class Woolworths extends Client implements ProductLoader
{

    protected $_baseUrl = 'https://wfs-appserver.wigroup.co/wfs/app/v4/products/';

    public function __construct()
    {

        $config = [
            'base_uri' => $this->_baseUrl,
            'headers' => [
                'apiId' => 'WEB',
                'apiKey' => 'iPhone',
                'sha1Password' => '53127b42-f4c9-4a0d-81c8-e45f8f31d6dd'
            ]
        ];

        parent::__construct($config);
    }


    /**
     * @param $id
     * @param bool $raw
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function find($id, $raw = true)
    {

        $product = Product::findOrCreate($id, self::class);

        $item = json_decode($this->get($id, [
            'query' => [
                'sku' => $id
            ]
        ])->getBody())->product;

        if ($raw) {
            return response()->json($item);
        }

        $data = [
            'external_id' => $id,
            'name' => $item->productName,
            'reference_id' => $id,
            'sku' => $item->sku,
            'category_name' => $item->categoryName,
            'brand' => object_get($item, 'attributes.Brands'),
            'class' => self::class,
            'description' => $item->longDescription,

            'price' => $item->fromPrice * 100,
            'retail_price' => $item->fromPrice * 100,
            'old_selling_price' => $item->fromPrice * 100,

            'data' => object_get($item, 'meta', []),
            'saving' => object_get($item, 'price.discount_percentage'),
            'is_on_special' => object_get($item, 'wasPrice') ? true : false,
            'stock_on_hand' => 0,

            'date_released' => Carbon::now(),
        ];

        $product->fill($data)->save();

        $images = (array) $item->auxiliaryImages;

        array_push($images, $item->imagePath);

        $this->processAssets($product, $item->auxiliaryImages);
        $this->processVariations($product, $item->otherSkus);

        return $product;

    }


    /**
     * @param Product $product
     * @param $assets
     */
    public function processAssets(Product $product, $assets)
    {
        // TODO: Implement processAseets() method.

        foreach ($assets as $item) {
            $product->assets()->updateOrCreate([
                'full_size' => $item->imagePath
            ], [
                'extension' => $item->mimeType,
                'full_size' => $item->imagePath,
                'url' => $item->externalImageRef,
                'thumb' => $item->externalImageRef,
                'tiny' => $item->externalImageRef,
            ]);
        }

    }

    public function processVariations(Product $product, $variations)
    {
        // TODO: Implement processVariations() method.

        foreach ($variations as $item) {
            $product->variations()->updateOrCreate([
                'sku_id' => $item->sku
            ], [
                'quantity' => 0,
                'sku' => $item->sku,
                'price' => $product->price,
                'status' => false ? 'Available' : 'Out of stock',
                'name' => object_get($item, 'displayName')
            ]);
        }

    }

    public function processBrand(Product $product, $brand)
    {
        // TODO: Implement processBrand() method.
    }
}
