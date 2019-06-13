<?php

namespace App\Service;

class StockExchangeService
{

    /**
     * This functions lonely purpose is to request data from the API for the wanted stocks and timeseries
     * I use alpha vantage as data provider, which limits me to 5 API requests per minute.
     * Feel free to implement any other data provider such as quandl and make a pull request so everybody can use it.
     *
     * The APIKEY for aphavantage can be inserted in the .env file, you can request your own free key under https://www.alphavantage.co
     *
     * @param string $stocks : the string with stocks from the request
     * @param string $timeseries: the timeseries the user requested
     *
     * @return array
     */
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
