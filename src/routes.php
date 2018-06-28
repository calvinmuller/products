<?php
//
//\Illuminate\Routing\Route::group(['middleware' => 'auth:api'], function ($router) {
//    $router->get('/products', 'Api\ProductController@index');
//});
$namespace = 'Istreet\Products\Controllers';

Route::name('api.')->prefix('api')->middleware(['api'])->namespace($namespace)->group(function ($router) {
    $router->apiResource('brand', 'Api\BrandsController');
    $router->apiResource('brand.products', 'Api\BrandsProductsController');

    $router->post('products/search', 'Api\SearchController@index');
    $router->get('products/{product}', 'ProductController@show');

    $router->group(['middleware' => 'auth:api'], function ($router) {
        $router->get('products', 'ProductController@index');

        $router->get('products/{product}/alert', 'Api\AlertController@index');
        $router->get('products/{product}/variations/{variation}/alert', 'Api\AlertController@index');
        $router->post('products/{product}/variations/{variation}/alert', 'Api\AlertController@store');
    });
});
