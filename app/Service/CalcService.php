<?php

namespace App\Service;

use MathPHP\Statistics\Correlation;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use App\Service\UtilService;

/**
 * This class does most of the calculations in this program except the Portfolio calculations, which got their own class.
 */

class CalcService
{
    /**
     * This function reworks the data for presentational use in the first Chart
     * It returns a nested array containing all the dates with an array of the stock symbols and the adjusted prices on close for every stock requested
     *
     * @param array $data : the stockdata we got from the StockExchangeService
     * @param string $timeseries : the timeseries the user requested. Its needed as the structure of the stockdata is different depending on the timeseries
     *
     * @return array
     */
    public function reworkStockData(array $data, string $timeseries)
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

    /**
     * This function calculates the returns for every stock in percent per entry
     * It takes the stockdata and divides the current stock price through the price from the predecessor.
     * It returns a nested array containing all the dates with an array of the stock symbols and the returns per stock per date in percent.
     *
     * @param array $data : the stockdata we got from the StockExchangeService
     * @param string $timeseries : the timeseries the user requested. Its needed as the structure of the stockdata is different depending on the timeseries
     *
     * @return array
     */
    public function returnsPreciseData(array $data, string $timeseries)
    {
        $unit = UtilService::setTimeSeries($timeseries);
        $returns = [];
        for ($i = 0; $i < count($data); $i++) {
            $start = $data[$i][$unit];
            $row = [];
            foreach ($start as $key => $value) {
                $row[$key] = $value['5. adjusted close'];
            }
            //reverse_rows is a array with every date as key and the matching price as value.
            $reverse_rows = array_reverse($row);
            $last = 0.0;
            foreach ($reverse_rows as $date => $price) {
                $returns[$date][$data[$i]['Meta Data']['2. Symbol']] = ((float)$last === 0.0) ? 0.0 : ((((float)$price)/(float)$last)-1);
                $last = $price;
            }
        }
        return $returns;
    }

    /**
     * This function calculates the average return for the whole timespan and multiply it by 250 to get the "annual" return
     * It takes the stockdata and divides the current stock price through the price from the predecessor.
     * It returns a nested array containing all the dates with an array of the stock symbols and the returns per stock per date in percent.
     *
     * @param array $data : the returns from returnsPreciseData
     * @param bool $annual : should the result be annual or not, defaults to true
     *
     * @return array
     */
    public function returnsMeanData(array $data, bool $annual = true)
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

    /**
     * This function calculates the covariance matrix for every entry and scalar multiplies it by 250 if annual is true
     * It takes the calculated percentages and calculates the covariance matrix of it. see https://en.wikipedia.org/wiki/Covariance_matrix to learn more.
     * I use the according MathPHP functions to accomplish this.
     * It returns the Matrix as a nested array.
     *
     * @param array $data : the returns from returnsPreciseData
     * @param bool $annual : should the result be annual or not, defaults to false
     *
     * @return array
     */
    public function cov(array $data, bool $annual = false)
    {
        $interim = [];
        foreach ($data as $date => $values) {
            $date = $date;
            $count = 0;
            foreach ($values as $price) {
                $interim[$count][] = (float)$price;
                $count++;
            }
        }
        $matrix = MatrixFactory::create($interim);
        if ($annual == false) {
            $cov = $matrix->covarianceMatrix();
        } else {
            $cov = $matrix->covarianceMatrix()->scalarMultiply(250);
        }
        return $cov->getMatrix();
    }
}
