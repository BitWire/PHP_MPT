<?php

namespace App\Service;

use App\Service\UtilService;

/**
 * This class is responsible for creating the Charts you see on the website
 */

class ChartService
{
    /**
     * This function creates the two Linecharts.
     *
     * Structure of $data:
     * array => [
     *  "Returns" => [
     *      "symbol" : "price",
     *      "symbol" : "price",
     *  ],
     * "Volatilities" => [
     *      "symbol" : "price",
     *      "symbol" : "price",
     *  ]
     *  ...
     * ]
     *
     * @param string $name : Name of the Chart
     * @param array $data : Data you want to have rendered as a Linechart
     *
     * @return void
     *
     */
    public function printLineChart(string $name, array $data)
    {
        $stockdata = \Lava::DataTable();
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

    /**
     * This function creates the Scatterchart.
     *
     * Structure of $data:
     * array => [
     *  "Returns" => [
     *      [0] : "percentage",
     *      [1] : "percentage",
     *      ...
     *  ],
     * "Volatilities" => [
     *      [0] : "percentage",
     *      [1] : "percentage",
     *      ...
     *  ],
     *  "Stock Weights" => [
     *      [0] : "percentage",
     *      [1] : "percentage",
     *      ...
     *  ]
     * ]
     *
     * @param string $name : Name of the Chart
     * @param array $data : Data you want to have rendered as a Scatterchart
     *
     * @return void
     *
     */

    public function printScatterChart(string $name, array $data)
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
            'legend' => [
                'position' => 'none'
            ],
            'hAxis' => [
                'title' => 'Volatilities'
            ],
            'vAxis' => [
                'title' => 'Returns'
            ]

        ]);
    }
}
