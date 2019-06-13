<?php

namespace App\Service;

use MathPHP\LinearAlgebra\Vector;
use MathPHP\Functions\Map\Multi;

use App\Service\UtilService;

class PortfolioService
{
    /**
     * This functions lonely purpose is to calculate as many portfolios as you configure in $num_portfolios.
     *
     * @param array $returnsMean : returns of CalcService->returnsMeanData
     * @param array $covPrecise : returns of CalcService->cov
     * @param array $covAnnual : return of CalcService->cov mit annual=true
     *
     * @return array
     */
    public function calculatePortfolio(array $returnsMean, array $covPrecise, array $covAnnual)
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
            $volatility = [];
            $interim = [];
            $interim2 = 0;

            $weights = $this->calcWeight($weights);
            //splits the $returnsMean in a keys and in a values array for later use.
            foreach ($returnsMean as $symbol => $value) {
                $symbols[] = $symbol;
                $returnsMeanClean[] = $value;
            }
            //Calculates the weights to actual percentages for use in the Chart.
            for ($j = 0; $j < count($symbols); $j++) {
                $symbolWeights[$symbols[$j]] = UtilService::toPercent($weights[$j]);
            }
            //Calculates the the returns for this portfolio.
            for ($j = 0; $j < count($weights); $j++) {
                $returns = $returns + $weights[$j]*$returnsMeanClean[$j];
            }
            //Calculates the volatility.
            for ($j = 0; $j < count($weights); $j++) {
                    $interim = (Multi::multiply($covAnnual[$j], $weights));
                    $interim2 = $interim2 + $weights[$j] * $interim[$j];
            }
            $volatility = sqrt($interim2);
            //Add the results of this portfolio to the overall results arrays
            $port_returns[] = $returns*100;
            $port_volatility[] = $volatility;
            $stock_weights[] = $symbolWeights;
        }
        return ['Returns' => $port_returns, 'Volatilites' => $port_volatility, 'Stock Weights' => $stock_weights];
    }

    /**
     * Calculates the actual weigths out of the random numbers generated.
     *
     * @param array $weights
     *
     * @return array
     */
    public function calcWeight(array $weights)
    {
        $weightsSum = 0;
        for ($j = 0; $j < count($weights); $j++) {
            $weightsSum = $weightsSum + $weights[$j];
        }
        for ($j = 0; $j < count($weights); $j++) {
            $weights[$j] = (($weights[$j] / $weightsSum));
        }
        return $weights;
    }
}
