<?php

namespace App\Service;

class StockExchangeService
{

    public function getData(string $input)
    {
        $client = new \GuzzleHttp\Client();
        $stocks = explode(",", $input);
        for ($i = 0; $i < count($stocks); $i++) {
            $response = $client->request('GET', 'https://www.alphavantage.co/query?function=TIME_SERIES_MONTHLY_ADJUSTED&symbol='. $stocks[$i] .'&datatype=json&apikey=' . env('APIKEY'));
            $data[$i] = json_decode($response->getBody()->getContents(), true);
            
            if (array_key_exists("Note", $data[$i]) || array_key_exists("Error Message", $data[$i])) {
                return false;
            }
        }
        return $data;
    }
}
