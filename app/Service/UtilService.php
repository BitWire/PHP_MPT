<?php

namespace App\Service;

/**
 * This class provides small functions which didnt fit in the other classes
 *
 */
class UtilService
{
    /**
     * This function returns the string needed for going through the arrays with the stockdata, based on the timeseries.
     * @param string $timeseries : the timeseries the user requested
     *
     * @return string
     */
    public static function setTimeSeries(string $timeseries)
    {
        if ($timeseries == 'daily') {
            $unit = 'Time Series (Daily)';
        } else {
            $unit = 'Monthly Adjusted Time Series';
        }
        return $unit;
    }

    /**
     * This function returns as many random numbers between certain thresholds as you want
     * @param int $size : how many random numbers you want
     *
     * @param int $min : lower threshold, defaults to 0
     *
     * @param int $max : upper threshold, defaults to 1
     *
     * @return array
     */
    public static function getFloatRand(int $size, int $min = 0, int $max = 1)
    {
        $output = [];
        for ($i = 0; $i < $size; $i++) {
            $output[] = ($min + ($max - $min) * (mt_rand() / mt_getrandmax()));
        }
        return $output;
    }

    /**
     * This function returns the input number to a displayable Percentage
     * @param $input : input number you want as a displayable Percentage
     *
     * @return string
     */
    public static function toPercent($input)
    {
        return round((float)$input * 100) . '%';
    }
}
