<?php

namespace Istreet\Products\Controllers\Api;

use App\Helpers\JsonApiResponseHelper;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Istreet\Products\Product;

class AlertController extends Controller
{
    //
    use JsonApiResponseHelper;


    public function show(Request $request, Product $product) {

        return $product;

    }


    public function store(Request $request, Product $product) {

        /** @var User $user */
        $user = $request->user();

        $alert = $product->alerts()->create([
            'user_id' => $user->id,
            'variation' => 10,
            'price' => 99,
            'info' => ''
        ]);

        $alert->save();

        return $alert;

    }
}
