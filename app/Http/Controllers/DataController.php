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
        $blob = $request->input();
        $StockExchangeService->getData($blob);
        $ChartService->printChart("u");

        echo \Lava::render('PieChart', 'OS', 'os-chart');
        return View::make('welcome')->with([]);
    }
}
