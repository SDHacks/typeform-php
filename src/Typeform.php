<?php

namespace Typeform;

use GuzzleHttp;

class Typeform {

    /** @var  GuzzleHttp\Client */
    protected $http;

    /** @var  string */
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->http = new GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.typeform.com/v0/form/'
        ]);
        $this->apiKey = $apiKey;
    }
}