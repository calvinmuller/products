<?php

namespace Istreet\Products;

use Illuminate\Support\ServiceProvider;
use Istreet\Products\Commands\SpreeIndex;
use Istreet\Products\Commands\SuperbalistIndex;
use Istreet\Products\Commands\TakealotIndex;
use Istreet\Products\Commands\ZandoIndex;

class ProductsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__ . '/config/products.php' => config_path('products.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                SuperbalistIndex::class,
                TakealotIndex::class,
                SpreeIndex::class,
                ZandoIndex::class,
            ]);
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->mergeConfigFrom(
            __DIR__ . '/config/products.php', 'products'
        );
    }
}
