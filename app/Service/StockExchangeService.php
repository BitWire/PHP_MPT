<?php

namespace App\Service;

class StockExchangeService
{

    public function getData(string $stocks, string $timeseries)
    {
        $client = new \GuzzleHttp\Client();
        $stocks = explode(",", $stocks);
        if ($timeseries == 'daily') {
            $unit = 'TIME_SERIES_DAILY_ADJUSTED';
        } else {
            $unit = 'TIME_SERIES_MONTHLY_ADJUSTED';
        }
        for ($i = 0; $i < count($stocks); $i++) {
            $response = $client->request('GET', 'https://www.alphavantage.co/query?function=' . $unit . '&symbol='. $stocks[$i] .'&datatype=json&apikey=' . env('APIKEY'));
            $data[$i] = json_decode($response->getBody()->getContents(), true);
            if (array_key_exists("Note", $data[$i]) || array_key_exists("Error Message", $data[$i])) {
                if (array_key_exists("Error Message", $data[$i])) {
                    \Log::info($data[$i]["Error Message"]);
                }
                return false;
            }
        }
        return $data;
    }
}
