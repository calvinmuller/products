<?php

namespace Istreet\Products\Commands;

use Illuminate\Console\Command;
use Superbalist\Brand as SBrand;
use Superbalist\Department;
use Superbalist\Product;

class SuperbalistIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superbalist:index';

    private $department;
    private $product;
    private $brand;

    private $departments = [
        'men',
        'women',
        'apartment'
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index Products on Superbalist';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Department $department, Product $product, SBrand $brand)
    {
        parent::__construct();

        $this->product = $product;
        $this->department = $department;
        $this->brand = $brand;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->processBrands();
        //

        /**
         * Process all products
         */
//        foreach ($this->departments as $department) {
//            $response = json_decode($this->department->find($department)->getBody());
//            $page = $response->page;
//            $pages = $response->pages;

        /**
         * Processing categories
         */
//            $this->processDepartment($response, $department);
//
//            $bar = $this->output->createProgressBar($response->total);

//            $this->info('Getting products from Superbalist for department ' . $department);
//
//            $this->processProducts($response->results, $bar);

//            for ($i = $page + 1; $i <= $pages; $i++) {
//                $response = json_decode($this->department->find($department, $i)->getBody());
//                $this->processProducts($response->results, $bar);
//            }

//        }

//        $bar->finish();

    }


//    private function processProducts($products, $bar)
//    {
//
//        $i = 1;
//
//        foreach ($products as $item) {
//
//            $product = \App\Product::findOrCreate($item->id);
//
//            if (($product->price != $item->price)) {
//
//                $product->fill([
//                        'name' => $item->name,
//                        'reference_id' => $item->id,
//                        'brand' => $item->designer,
//                        'class' => Product::class,
////                        'category_name' => $result->category_name,
////                        'status' => $result->status,
////                        'discount' => $result->discount,
////                        'quantity' => $result->stock_quantity,
////                        'discovery' => $result->discovery,
//                        'price' => $item->price,
//                        'retail_price' => $item->retail_price,
//                        'data' => []
//                    ]
//                )->save();
//
////                $this->processVariations($product, $result->variations);
//                $this->processAssets($product, [$item->asset]);
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
//                'full_size' => $item->full_size
//            ], [
//                'extension' => $item->extension,
//                'full_size' => $item->full_size,
//                'url' => $item->url,
//                'thumb' => $item->thumb,
//                'tiny' => $item->tiny,
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
//                'sku_id' => $item->sku_id
//            ], [
//                'quantity' => $item->quantity,
//                'sku_id' => $item->sku_id,
//                'price' => $item->price,
//                'status' => $item->status,
//                'name' => $item->name,
//            ]);
//        }
//    }


//    private function processDepartment($response, $currentDepartment)
//    {
//
//        $this->info('Indexing categories & departments');
//
//        $dept = collect($response->departments)
//            ->where('slug', '=', $currentDepartment)
//            ->first();
//
//        $department = \App\Category::findOrCreate($dept->id);
//
//        $department->fill([
//            'reference_id' => $dept->id,
//            'class' => 'Superbalist\Category',
//            'resolved_id' => $dept->resolved_id,
//            'path' => $dept->path,
//            'slug' => $dept->slug,
//            'url' => $dept->url,
//            'title' => $dept->title,
//            'parent_path' => $dept->parent_path
//        ]);
//
//        $department->save();
//
//        $this->processCategories($department, $dept->categories);
//    }


    /**
     * Recursive function to process all categories
     * @param $cats
     */
//    private function processCategories(\App\Category $department, $cats, $parentCategory = null)
//    {
//
//        foreach ($cats as $cat) {
//
//            $category = \App\Category::findOrCreate($cat->title);
//
//            $category->fill([
//                'reference_id' => $cat->id,
//                'department_id' => $department->id,
//                'class' => 'Superbalist\Category',
//                'resolved_id' => $cat->resolved_id,
//                'path' => $cat->path,
//                'slug' => $cat->slug,
//                'url' => $cat->url,
//                'title' => $cat->title,
//                'parent_path' => $cat->parent_path
//            ]);
//
//            if ($parentCategory) {
//                $category->category_id = $parentCategory->id;
//            } else {
//                $category->category_id = $department->id;
//            }
//
//            $category->save();
//
//            if (property_exists($cat, 'categories') && count($cat->categories) > 0) {
//                $this->processCategories($department, $cat->categories, $category);
//            }
//
//        }
//
//    }

    private function processBrands()
    {

        $this->info('Indexing brands');

        $response = json_decode($this->brand->all()->getBody());

        $brands = $response->brands;

        $bar = $this->output->createProgressBar(count($brands));

        foreach ($brands as $item) {

            $brand = \Istreet\Products\Brand::findOrCreate($item->name, [
                'reference_id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
            ]);

            $brand->save();

            $brand->asset()->updateOrCreate([
                'full_size' => $item->asset->full_size
            ], [
                'extension' => $item->asset->extension,
                'full_size' => $item->asset->full_size,
                'url' => $item->asset->url,
                'thumb' => $item->asset->thumb,
                'tiny' => $item->asset->tiny,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->info('');

    }
}
