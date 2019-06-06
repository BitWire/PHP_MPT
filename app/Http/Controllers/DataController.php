<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

use App\Service\AnalyticsService;
use App\Service\ChartService;

class DataController extends Controller
{
    public function input(Request $request)
    {
        $ChartService = new ChartService;
        $AnalyticsService = new AnalyticsService;
        $blob = $request->input();
        $data = $AnalyticsService->getDataAndProcess($blob['objectId']);
        $ChartService->printChart($data);
        $sessions = 0;
        $users = 0;
        foreach ($data as $key => $value) {
            $sessions = $sessions + $value[0];
            $users = $users + $value[1];
            $key = 0;
        }

        echo \Lava::render('PieChart', 'OS', 'os-chart');
        return View::make('welcome')->with(['objectId'=> $blob['objectId'],'sessions' => $sessions , 'users' => $users]);
    }
}
