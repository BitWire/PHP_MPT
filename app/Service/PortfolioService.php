<?php

namespace App\Service;

use MathPHP\LinearAlgebra\Vector;
use MathPHP\Functions\Map\Multi;

use App\Service\UtilService;

class PortfolioService
{
    public function calculatePortfolio($returnsMean, $covPrecise, $covAnnual)
    {
        $port_returns = [];
        $port_volatility = [];
        $stock_weights = [];
        $num_assets = count($covPrecise);
        $num_portfolios = 25000;
        
        for ($i = 0; $i < $num_portfolios; $i++) {
            $weights = UtilService::getFloatRand($num_assets, 0, 1);
            $returns = 0;
            $returnsMeanClean = [];
            $symbols = [];
            $symbolWeights = [];
            $covAnnualClean = [];
            $volatility = [];
            $interim = [];
            $interim2 = 0;

            $weights = $this->calcWeight($weights);

            foreach ($returnsMean as $symbol => $value) {
                $symbols[] = $symbol;
                $returnsMeanClean[] = $value;
            }

            for ($j = 0; $j < count($symbols); $j++) {
                $symbolWeights[$symbols[$j]] = UtilService::toPercent($weights[$j]);
            }

            foreach ($covAnnual as $value) {
                $count = 0;
                foreach ($value as $price) {
                    $covAnnualClean[$count][] = $price;
                    $count++;
                }
            }
            for ($j = 0; $j < count($weights); $j++) {
                $returns = $returns + $weights[$j]*$returnsMeanClean[$j];
            }
            for ($j = 0; $j < count($weights); $j++) {
                    $interim = (Multi::multiply($covAnnualClean[$j], $weights));
                    $interim2 = $interim2 + $weights[$j] * $interim[$j];
            }
            $volatility = sqrt($interim2);
            $port_returns[] = $returns*100;
            $port_volatility[] = $volatility;
            $stock_weights[] = $symbolWeights;
        }
        return ['Returns' => $port_returns, 'Volatilites' => $port_volatility, 'Stock Weights' => $stock_weights];
    }
    public function calcWeight($weights)
    {
        $weightsSum = 0;
        for ($j = 0; $j < count($weights); $j++) {
            $weightsSum = $weightsSum + $weights[$j];
        }
        for ($j = 0; $j < count($weights); $j++) {
            $weights[$j] = ($weights[$j] / $weightsSum);
        }
        return $weights;
    }
}
