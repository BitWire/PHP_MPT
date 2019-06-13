<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

use App\Service\StockExchangeService;
use App\Service\ChartService;
use App\Service\CalcService;
use App\Service\PortfolioService;

/**
 * This is the DataController. This class controls the whole program basically.
 * The function input checks for the 2 GET parameters 'timeseries' and 'stocks',
 * gives errors back and calls all the functions needed to calculate and render the output you see as a webpage.
 * You can use up to five stocks per call and choose between 'daily' and 'monthly' stockdata.
 *  - 'daily' gives you daily stockdata for approximately the last 6 months.
 *  - 'monthly' gives you the stockdata of every last day of a month for a timespan as long as possible.
 * $code is generally used for errorhandling with the user
 */


class DataController extends Controller
{
    /**
     * @param Request $request : the request made by calling the website
     *
     * @return View
     */
    public function input(Request $request)
    {
        //Initialize all the needed Classes
        $ChartService = new ChartService;
        $StockExchangeService = new StockExchangeService;
        $CalcService = new CalcService;
        $PortfolioService = new PortfolioService;

        //Check if the inputs are there and use them or give an error if necessary
        $input = $request->input();
        $timeseries = 'daily';
        if (array_key_exists('timeseries', $input)) {
            $timeseries = $input['timeseries'];
        }
        if (array_key_exists('stocks', $input)) {
            //request data from the API for the wanted stocks and timeseries
            $stockdata = $StockExchangeService->getData($input['stocks'], $timeseries);
            //Check if we got actually data back or if we got too much usage on the stock api
            if ($stockdata != false) {
                //rework the data for presentational use in the first Chart
                $data = $CalcService->reworkStockData($stockdata, $timeseries);
                //calculate the returns for every stock in percent per entry
                $returnsPrecise = $CalcService->returnsPreciseData($stockdata, $timeseries);
                //calculate the average return for the whole timespan and multiply it by 250 to get the "annual" return
                $returnsMean = $CalcService->returnsMeanData($returnsPrecise, true);
                //calculate the covariance matrix for the precise returns
                $covPrecise = $CalcService->cov($returnsPrecise);
                //calculate the covariance matrix for the "annual" returns
                $covAnnual = $CalcService->cov($returnsPrecise, true);
                /**
                 * use the "annual" returns, the covariance matrix of the precise returns and the covariance matrix for "annual" returns
                 * to calculate randomly weighted portfolios
                 */
                $portfolios = $PortfolioService->calculatePortfolio($returnsMean, $covPrecise, $covAnnual);
                
                //print all the data in charts
                $ChartService->printLineChart('Stockprice', $data);
                $ChartService->printLineChart('ReturnsPrecise', $returnsPrecise);
                $ChartService->printScatterChart('Portfolios', $portfolios);
                //and render all the charts in the according blade
                echo \Lava::render('LineChart', 'Stockprice', 'stockprice-chart');
                echo \Lava::render('ScatterChart', 'Portfolios', 'portfolio-chart');
                echo \Lava::render('LineChart', 'ReturnsPrecise', 'returnsprecise-chart');

                $code = 200;
            } else {
                $code = 503;
            }
        } else {
            $code = 418;
        }
        return View::make('welcome')->with(['code' => $code]);
    }
}
