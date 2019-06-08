<?php

namespace App\Service;

class CalcService
{
    public function reworkStockData($data, $timeseries)
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
                $row[$key][$data[$i]['Meta Data']['2. Symbol']] = $value['5. adjusted close'];
            }
        }
        $reverse_rows = array_reverse($row);
        return $reverse_rows;
    }
}
