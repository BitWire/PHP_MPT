<?php

namespace App\Service;

class ChartService
{
    public function printChartStock($name, $data)
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
}
