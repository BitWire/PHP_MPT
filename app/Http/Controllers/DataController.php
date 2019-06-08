<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

use App\Service\StockExchangeService;
use App\Service\ChartService;
use App\Service\CalcService;

class DataController extends Controller
{
    public function input(Request $request)
    {
        $ChartService = new ChartService;
        $StockExchangeService = new StockExchangeService;
        $CalcService = new CalcService;

        $input = $request->input();
        $timeseries = 'monthly';
        if (array_key_exists('timeseries', $input)) {
            $timeseries == $input['timeseries'];
        }
        $stockdata = $StockExchangeService->getData($input['stock'], $timeseries);
        if ($stockdata != false) {
            $data = $CalcService->reworkStockData($stockdata, $timeseries);
            $ChartService->printChartStock($data, $timeseries);
            echo \Lava::render('LineChart', 'Stockprice', 'stockprice-chart');
            $code = 200;
        } else {
            $code = 500;
        }
        return View::make('welcome')->with(['code' => $code]);
    }
}
