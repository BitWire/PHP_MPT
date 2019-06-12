<?php

namespace App\Service;

use MathPHP\Statistics\Correlation;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use App\Service\UtilService;

class CalcService
{
    public function reworkStockData($data, $timeseries)
    {
        $row = [];
        $unit = UtilService::setTimeSeries($timeseries);
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
        $unit = UtilService::setTimeSeries($timeseries);
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
        $annual = 0;
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
        }
        if ($annual == true) {
            $interim[$symbol] = ((float)$value*250);
        }
        return $interim;
    }

    public function covPrecise($data, $annual = false)
    {
        $interim = [];
        $symbols = [];
        foreach ($data as $date => $values) {
            $date = $date;
            $count = 0;
            foreach ($values as $price) {
                $interim[$count][] = (float)$price;
                $count++;
            }
        }
        $matrix = MatrixFactory::create($interim);
        $symbols = array_keys($interim);
        if ($annual == false) {
            $cov = $matrix->covarianceMatrix();
        } else {
            $cov = $matrix->covarianceMatrix()->scalarMultiply(250);
        }
        return $cov->getMatrix();
    }
}
