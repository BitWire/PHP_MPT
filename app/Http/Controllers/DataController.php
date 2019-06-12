<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

use App\Service\StockExchangeService;
use App\Service\ChartService;
use App\Service\CalcService;
use App\Service\PortfolioService;

class DataController extends Controller
{
    public function input(Request $request)
    {
        $ChartService = new ChartService;
        $StockExchangeService = new StockExchangeService;
        $CalcService = new CalcService;
        $PortfolioService = new PortfolioService;

        $input = $request->input();
        $timeseries = 'monthly';
        if (array_key_exists('timeseries', $input)) {
            $timeseries = $input['timeseries'];
        }
        $stockdata = $StockExchangeService->getData($input['stock'], $timeseries);

        if ($stockdata != false) {
            $data = $CalcService->reworkStockData($stockdata, $timeseries);
            $returnsPrecise = $CalcService->returnsPreciseData($stockdata, $timeseries);
            $returnsMean = $CalcService->returnsMeanData($returnsPrecise);
            $covPrecise = $CalcService->covPrecise($returnsPrecise);
            $covAnnual = $CalcService->covPrecise($returnsPrecise, true);
            $ChartService->printLineChart('Stockprice', $data);
            $ChartService->printLineChart('ReturnsPrecise', $returnsPrecise);

            $portfolios = $PortfolioService->calculatePortfolio($returnsMean, $covPrecise, $covAnnual);
            $ChartService->printScatterChart('Portfolios', $portfolios);
            echo \Lava::render('LineChart', 'Stockprice', 'stockprice-chart');
            echo \Lava::render('ScatterChart', 'Portfolios', 'portfolio-chart');
            echo \Lava::render('LineChart', 'ReturnsPrecise', 'returnsprecise-chart');
            $code = 200;
        } else {
            $code = 500;
        }
        return View::make('welcome')->with(['code' => $code]);
    }
}
