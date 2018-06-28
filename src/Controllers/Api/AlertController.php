<?php

namespace Istreet\Products\Controllers\Api;

use App\Helpers\JsonApiResponseHelper;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Istreet\Products\Product;
use Istreet\Products\Requests\CreateAlertRequest;
use Istreet\Products\Variation;

class AlertController extends Controller
{
    //
    use JsonApiResponseHelper;


    /**
     * @param Request $request
     * @param Product $product
     * @param Variation $variation
     * @return mixed
     */
    public function index(Request $request, Product $product, Variation $variation)
    {
        return $request->user()->alerts
            ->with('product')
            ->where('variation_id', '=', $variation->id)
            ->first();
    }


    /**
     *
     * @param Request $request
     * @param Product $product
     * @return Product
     */
    public function show(Request $request, Product $product) {

        return $product;

    }


    /**
     *
     * @param CreateAlertRequest $request
     * @param Product $product
     * @param Variation $variation
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(CreateAlertRequest $request, Product $product, Variation $variation) {

        /** @var User $user */
        $user = $request->user();

        $alert = $variation->alert()->updateOrCreate([
            'user_id' => $user->id,
        ],
            ['price' => $request->get('price')]
        );

        $alert->save();

        return $alert;

    }


}
