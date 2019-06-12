<?php

namespace App\Service;

class UtilService
{
    public static function setTimeSeries($timeseries)
    {
        if ($timeseries == 'daily') {
            $unit = 'Time Series (Daily)';
        } else {
            $unit = 'Monthly Adjusted Time Series';
        }
        return $unit;
    }

    public static function getFloatRand($size, $min = 0, $max = 1)
    {
        $output = [];
        for ($i = 0; $i < $size; $i++) {
            $output[] = ($min + ($max - $min) * (mt_rand() / mt_getrandmax()));
        }
        return $output;
    }

    public static function toPercent($input)
    {
        return round((float)$input * 100) . '%';
    }
}
