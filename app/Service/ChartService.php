<?php

namespace App\Service;

use App\Service\UtilService;

class ChartService
{
    public function printLineChart($name, $data)
    {
        $stockdata = \Lava::DataTable();  // Lava::DataTable() if using Laravel
        $stockdata->addStringColumn('datetime');
        $keys = array_keys(end($data));
        reset($data);
        for ($i = 0; $i < count($keys); $i++) {
            \Log::info('Array point: ' . $keys[$i]);
            $stockdata->addNumberColumn($keys[$i]);
        }
        foreach ($data as $date => $values) {
            $interim = [];
            $interim[] = $date;
            foreach ($values as $price) {
                $interim[] = $price;
            }
            $stockdata->addRow($interim);
        }
        \Lava::LineChart($name, $stockdata, [
            'width' => 1280,
            'height' => 720,
            'title' => $name,
        ]);
    }

    public function printScatterChart($name, $data)
    {
        $stockdata = \Lava::DataTable();  // Lava::DataTable() if using Laravel
        $stockdata->addNumberColumn('Volatilites');
        $stockdata->addNumberColumn('Returns');
        $stockdata->addStringColumn('Weights', null, 'tooltip');
        for ($i = 0; $i < count($data['Returns']); $i++) {
            $stockKeys = array_keys($data['Stock Weights'][$i]);
            $stockValues = array_values($data['Stock Weights'][$i]);
            $tooltip = 'Volatilities: ' . UtilService::toPercent((float)$data['Volatilites'][$i]) . "\n" . 'Returns: ' . UtilService::toPercent((float)$data['Returns'][$i]) . "\n";
            for ($j = 0; $j < count($stockKeys); $j++) {
                $tooltip = $tooltip . $stockKeys[$j] . ' ' . $stockValues[$j] . "\n";
            }
            $stockdata->addRow([(float)$data['Volatilites'][$i],(float)$data['Returns'][$i], $tooltip]);
        }
        \Lava::ScatterChart($name, $stockdata, [
            'width' => 1280,
            'height' => 720,
            'title' => $name,
            'hAxis' => [
                'title' => 'Volatilities'
            ],
            'vAxis' => [
                'title' => 'Returns'
            ]

        ]);
    }
}
