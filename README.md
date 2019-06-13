# The Modern Portfolio Theory

This is a PHP implementation of the Modern Portfolio Theory. I made this as part of a Seminar essay and presentation.
This Project is based on Laravel 5.8 and PHP 7.1 and the medium articles about the MPT by Bernard Brenyah. 
Really worth a read! 

- [Markowitz’s Efficient Frontier in Python [Part 1/2]](https://medium.com/python-data/effient-frontier-in-python-34b0c3043314)
- [Efficient Frontier & Portfolio Optimization with Python [Part 2/2]](https://medium.com/python-data/efficient-frontier-portfolio-optimization-with-python-part-2-2-2fe23413ad94)

## Installation

In root directory:
```
composer install
```

The `.env.example` to `.env` and app key generation should be done automatically.
Edit the `.env` File to your needs. Dont forget to input an APIKEY!

## Usage
Start the debugserver with the command `php artisan serve` in the root directory of the Project.

In a browser, open: 

```
127.0.0.1:8000/?stocks=XXX,XXX,XXX,XXX
```
where XXX is a valid stock symbol. you can use up to 5 stocks. Optionally you can use timeseries to change the requested data between monthly adjusted (monthly) and daily adjusted (daily) stock prices. This defaults to daily. 

## Used Libarys:

 - [The Laravel Framework](https://github.com/laravel/laravel)
 - [Guzzle](http://docs.guzzlephp.org/en/stable/overview.html)
 - [LavaCharts](https://github.com/kevinkhill/lavacharts)
 - [MathPHP](https://github.com/markrogoyski/math-php)


 ## Setup to contribute
Set up grumPHP to sniff commits.
```
 php ./vendor/bin/grumphp git:init --config=./grumphp.yml
```
To autofix possible Codestyle errors, use:
```
composer autofix-phpcs
```
