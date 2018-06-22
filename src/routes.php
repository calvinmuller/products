<?php
//
//\Illuminate\Routing\Route::group(['middleware' => 'auth:api'], function ($router) {
//    $router->get('/products', 'Api\ProductController@index');
//});
$namespace = 'Istreet\Products\Controllers';

Route::group(['prefix' => 'api/products', 'middleware' => 'api', 'namespace' => $namespace], function ($router) {

    $router->post('/search', 'Api\SearchController@index');
    $router->get('/{product}', 'ProductController@show');

    $router->group(['middleware' => 'auth:api'], function ($router) {
        $router->get('/', 'ProductController@index');
        $router->post('{product}/alert', 'Api\AlertController@store');
    });

});
