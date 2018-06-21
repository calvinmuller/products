<?php

namespace Istreet\Products\Controllers\Api;


use App\Helpers\JsonApiResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Istreet\Products\Helpers\Matcher;

class SearchController extends Controller
{
    use JsonApiResponseHelper;

    private $matcher;

    /**
     * SearchController constructor.
     * @param Matcher $matcher
     */
    public function __construct(Matcher $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $url = $request->get('url');
        $raw = $request->get('raw', false);

        return $this->matcher->match($url, $raw);
    }

}
