<?php

namespace App\Service;

class ChartService
{
    public function printChartStock($data)
    {
        $stockdata = \Lava::DataTable();  // Lava::DataTable() if using Laravel
        $stockdata->addStringColumn('datetime');
        $end = end($data);
        reset($data);
        foreach ($end as $key => $value) {
            \Log::info('Array point: ' . $key);
            $stockdata->addNumberColumn('adjusted close of '. $key);
            $value = 0;
        }
        foreach ($data as $date => $values) {
            $interim = [];
            $interim[] = $date;
            foreach ($values as $price) {
                $interim[] = $price;
            }
            $stockdata->addRow($interim);
        }
        \Lava::LineChart('Stockprice', $stockdata, [
            'width' => 1280,
            'height' => 720,
        ]);
    }
}
