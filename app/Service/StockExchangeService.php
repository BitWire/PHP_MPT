<?php

namespace App\Service;

use GuzzleHttp\Psr7\Request;

class StockExchangeService
{

    public function getData(string $input)
    {
        $request = Request.get('https://www.alphavantage.co/query?function=TIME_SERIES_MONTHLY_ADJUSTED&symbol='. $input .'&datatype=json&apikey=' . env('APIKEY'));
        return $request;
    }
}
