<?php

namespace App\Service;

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
        for ($i = 0; $i < count($data['Returns']); $i++) {
            $stockdata->addRow([(float)$data['Volatilites'][$i],(float)$data['Returns'][$i]]);
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
