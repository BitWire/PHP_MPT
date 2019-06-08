<?php

namespace App\Service;

class ChartService
{
    public function printChart($data, $timeseries)
    {
        $stockdata = \Lava::DataTable();  // Lava::DataTable() if using Laravel
        $stockdata->addStringColumn('datetime');
        $row = [];
        if ($timeseries == 'daily') {
            $unit = 'Time Series (Daily)';
        } else {
            $unit = 'Monthly Adjusted Time Series';
        }
        for ($i = 0; $i < count($data); $i++) {
            \Log::info('Array punkt: ' . $i);
            $stockdata->addNumberColumn('adjusted close of '. $data[$i]['Meta Data']['2. Symbol']);
            $start = $data[$i][$unit];
            foreach ($start as $key => $value) {
                \Log::info($key . ' : ' . $value['5. adjusted close']);
                $row[$key][] = $value['5. adjusted close'];
            }
        }
        $reverse_rows = array_reverse($row);
        foreach ($reverse_rows as $key => $value) {
            $interim = [];
            $interim[] = $key;
            for ($i = 0; $i < count($value); $i++) {
                $interim[] = $value[$i];
            }
            $stockdata->addRow($interim);
        }
        \Lava::LineChart('OS', $stockdata, [
            'width' => 1280,
            'height' => 720,
        ]);
    }
}
