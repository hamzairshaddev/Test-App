<?php

namespace App\Services;

use Http;
 
class QuotesService {

    private $url;
    private $headers;
  
    public function __construct()
    {
        $this->url = "https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data";
        $this->headers =  [
            'X-RapidAPI-Key'=> config('services.rapidapi.key'),
            'X-RapidAPI-Host'=> config('services.rapidapi.host')
        ];
    }
 
    public function getData(array $filters): array
    {
        try{
            return Http::withHeaders($this->headers)->get($this->url, $filters)->json();
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json($e->getMessage(), 500);
        }
    }
}