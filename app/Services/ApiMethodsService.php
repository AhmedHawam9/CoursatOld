<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class ApiMethodsService
{
    protected $headers;
    protected $queryParameters;

    public function __construct()
    {
        $this->headers = [];
        $this->queryParameters = [];
    }
    public function withHeaders(array $headers = [])
    {
        $this->headers = $headers;
        return $this;
    }

    public function withQuery(array $queryParameters = [])
    {
        $this->queryParameters = $queryParameters;
        return $this;
    }
    public function getApiData($url)
    {
        $response = Http::withHeaders($this->headers)->withQueryParameters($this->queryParameters)
            ->get($url, [$this->queryParameters]);

        return $response;

        // Handle the response as before
    }

    public function postApiData($url, $data)
    {
        $response = Http::withHeaders($this->headers)->
            post($url, $data);
        return $response;
        // Handle the response as before
    }
}
