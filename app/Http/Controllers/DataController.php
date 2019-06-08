<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

use App\Service\StockExchangeService;
use App\Service\ChartService;

class DataController extends Controller
{
    public function input(Request $request)
    {
        $ChartService = new ChartService;
        $StockExchangeService = new StockExchangeService;
        $input = $request->input();
        $data = $StockExchangeService->getData($input['stock'], $input['timeseries']);
        if ($data != false) {
            $ChartService->printChart($data);
            echo \Lava::render('LineChart', 'OS', 'os-chart');
            $code = 200;
        } else {
            $code = 500;
        }
        return View::make('welcome')->with(['code' => $code]);
    }
}
