<?php
namespace Istreet\Products\Suppliers\Zando;


use GuzzleHttp\Client;

class Api extends Client {

    public function __construct()
    {
        $config = [
            'base_uri' => 'https://api.zando.co.za/',
            'verify' => false,
            'headers' => [
                'api-version' => '115'
            ]
        ];

        parent::__construct($config);
    }
}
