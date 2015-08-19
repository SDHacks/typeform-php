<?php

namespace Typeform;

use GuzzleHttp;

class Typeform {

    /** @var  GuzzleHttp\Client  */
    protected $http;

    /** @var  string Typeform API key */
    protected $apiKey;

    /** @var  string Typeform base URI */
    protected $baseUri = 'https://api.typeform.com/v0/form/';

    public function __construct($apiKey)
    {
        $this->http = new GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => $this->baseUri
        ]);
        $this->apiKey = $apiKey;
    }

    public function getForm($formId, $raw = false, $limit = 50, $page = 0)
    {
        return new Form($this->getHttp(), $this->getApiKey(), $formId, $raw, $limit = 50, $page = 0);
    }

    /**
     * @return GuzzleHttp\Client
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * @param GuzzleHttp\Client $http
     */
    public function setHttp($http)
    {
        $this->http = $http;
    }

    /**
     * @return string
     */
    protected function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    protected function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param string $uri
     */
    public function setBaseUri($uri)
    {
        $this->baseUri = $uri;
    }
}