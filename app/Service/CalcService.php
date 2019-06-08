<?php

namespace App\Service;

use MathPHP\Statistics\Correlation;

class CalcService
{
    public function setTimeSeries($timeseries)
    {
        if ($timeseries == 'daily') {
            $unit = 'Time Series (Daily)';
        } else {
            $unit = 'Monthly Adjusted Time Series';
        }
        return $unit;
    }
    public function reworkStockData($data, $timeseries)
    {
        $stockdata = \Lava::DataTable();  // Lava::DataTable() if using Laravel
        $stockdata->addStringColumn('datetime');
        $row = [];
        $unit = $this->setTimeSeries($timeseries);
        for ($i = 0; $i < count($data); $i++) {
            $start = $data[$i][$unit];
            foreach ($start as $key => $value) {
                $row[$key][$data[$i]['Meta Data']['2. Symbol']] = $value['5. adjusted close'];
            }
        }
        $reverse_rows = array_reverse($row);
        return $reverse_rows;
    }

    public function returnsPreciseData($data, $timeseries)
    {
        $unit = $this->setTimeSeries($timeseries);
        $returns = [];
        for ($i = 0; $i < count($data); $i++) {
            $start = $data[$i][$unit];
            $row = [];
            foreach ($start as $key => $value) {
                $row[$key] = $value['5. adjusted close'];
            }
            $reverse_rows = array_reverse($row);
            $last = 0.0;
            foreach ($reverse_rows as $date => $price) {
                if ((float)$last != 0.0) {
                    $returns[$date][$data[$i]['Meta Data']['2. Symbol']] = ((((float)$price)/(float)$last)-1);
                    $last = $price;
                    continue;
                }
                $returns[$date][$data[$i]['Meta Data']['2. Symbol']] = 0.0;
                $last = $price;
            }
        }
        return $returns;
    }

    public function returnsMeanData($data, $annual = true)
    {
        $interim = [];
        foreach ($data as $values) {
            foreach ($values as $symbol => $price) {
                if (!array_key_exists($symbol, $interim)) {
                    $interim[$symbol] = 0.0;
                    continue;
                }
                $interim[$symbol] = ((float)$interim[$symbol] + (float)$price);
            }
        }
        foreach ($interim as $symbol => $value) {
            $interim[$symbol] = ((float)$value/count($data));
            if ($annual == true) {
                $interim[$symbol] = ((float)$value*250);
            }
        }
        return $interim;
    }

    public function covPrecise($data, $annual = false)
    {
        $interim = [];
        $symbols = [];
        foreach ($data as $date => $values) {
            $date = $date;
            foreach ($values as $symbol => $price) {
                $interim[$symbol][] = (float)$price;
            }
        }
        $symbols = array_keys($interim);
        $cov = [];
        for ($i = 0; $i < count($interim); $i++) {
            for ($j = 0; $j < count($interim); $j++) {
                if ($annual == false) {
                    $cov[$symbols[$i]][$symbols[$j]] = Correlation::covariance($interim[$symbols[$i]], $interim[$symbols[$j]]);
                    continue;
                }
                $cov[$symbols[$i]][$symbols[$j]] = (Correlation::covariance($interim[$symbols[$i]], $interim[$symbols[$j]]))*250;
            }
        }
        return $cov;
    }
}
