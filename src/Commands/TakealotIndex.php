<?php

namespace Istreet\Products\Commands;

use Illuminate\Console\Command;
//use TakealotApi\Offer;
use TakealotApi\Product;

class TakealotIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'takealot:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index Takealot';

    private $client;
    private $offers;
    private $products;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\TakealotApi\Api $api, Product $product)
    {
        parent::__construct();
        $this->client = $api;
        $this->products = $product;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        $pageSize = 100;
        //
//        $products = json_decode(
//            $this->products->all([
//                'start' => 0,
//                'rows' => $pageSize,
//                'filter' => 'Available:true'
//            ])->getBody()
//        )->results;

//        $bar = $this->output->createProgressBar($products->num_found);

//        $this->processProducts($products->productlines, $bar);

//        for ($i = $pageSize; $i <= $products->num_found / $pageSize; $i += $pageSize) {
//            $response = json_decode($this->products->all([
//                'start' => $i,
//                'rows' => $pageSize,
//                'filter' => 'Available:true'
//            ])->getBody())->results;
//
//            $this->processProducts($response->productlines, $bar);
//        }
//
//        $bar->finish();
    }


//    public function processDepartments($departments)
//    {
//
//        foreach ($departments as $department) {
//
////            print_r($department);
////
////            exit();
////            $listing = $this->offers->find($department->id);
////
////
////            $data = json_decode($listing->getBody());
//        }
//
//
//    }

//    private function processProducts($products, $bar)
//    {
//
//        $i = 1;
//
//        foreach ($products as $item) {
//
//            $product = \App\Product::findOrCreate($item->sku_id);
//
//            if ($item->selling_price != $product->price) {
//
//                $response = json_decode($this->products->find($item->sku_id)->getBody())
//                    ->response;
//
//                $product->fill([
//                        'name' => $response->title,
//                        'reference_id' => $item->sku_id,
//                        'category_name' => object_get($response, 'categories.0.name'),
//                        'brand' => object_get($response, 'brand.name'),
//                        'class' => Product::class,
//                        'description' => $response->description,
//                        'price' => $response->selling_price,
//                        'retail_price' => $response->old_selling_price,
//                        'data' => object_get($response, 'meta'),
//                    ]
//                )->save();
//
//                $this->processAssets($product, $response->images);
//                $this->processVariations($product, $response->skus);
//            }
//
//            $i++;
//
//            $bar->advance();
//
//        }
//    }


    /**
     * @param \App\Product $product
     * @param $assets
     */
//    private function processAssets(\App\Product $product, $assets)
//    {
//        foreach ($assets as $item) {
//            $product->assets()->updateOrCreate([
//                'full_size' => $item->full
//            ], [
//                'extension' => 'jpg',
//                'full_size' => $item->full,
//                'url' => $item->full,
//                'thumb' => $item->small,
//                'tiny' => $item->small,
//            ]);
//        }
//
//    }


    /**
     * @param \App\Product $product
     * @param $variations
     */
//    private function processVariations(\App\Product $product, $variations)
//    {
//        foreach ($variations as $item) {
//            $product->variations()->updateOrCreate([
//                'sku_id' => $item->id
//            ], [
//                'quantity' => $item->stock_on_hand,
//                'sku_id' => $item->id,
//                'price' => $item->selling_price,
//                'status' => ($item->is_available) ? 'Available' : 'Out of stock',
//                'name' => object_get($item, 'size.value')
//            ]);
//        }
//    }
}
