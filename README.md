# Bol.com Retailer API client


![Bol API Retailer Version][ico-bol-retailer-version]
![PHP version][ico-php-version]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Tests][ico-tests]][link-tests]
[![StyleCI][ico-code-style]][link-code-style]
[![Total Downloads][ico-downloads]][link-downloads]


[Bol Retailer API documentation](https://developers.bol.com/)

## Requirements

To use the Bol.com Retailer API client, the following things are required:

* Generate your [API Key](https://bol.com/sdd)

## Installation

Install package using composer

``` bash
composer require budgetlens/bol-retailer-api
```

## Getting started

Initialize the Api client using the client ID / secret defined in an .env file. 

``` php
$client = new \Budgetlens\BolRetailerApi\Client();
```
Or Use your own config.
``` php
$client = new \Budgetlens\BolRetailerApi\Client(new CustomApiConfig());
```

# Examples
*for more examples see "tests folder"*

## Commmissions

### Get all commissions and reductions by EAN in bulk
```php
$items = [
    [
        "ean" => "8712626055150",
        "condition" => "NEW",
        "unitPrice" => "34.99"
    ], [
        "ean" => "8804269223123",
        "condition" => "NEW",
        "unitPrice" => "699.95"
    ], [
        "ean" => "8712626055143",
        "condition" => "GOOD",
        "unitPrice" => "24.50"
    ], [
        "ean" => "0604020064587",
        "condition" => "NEW",
        "unitPrice" => "24.95"
    ], [
        "ean" => "8718526069334",
        "condition" => "NEW",
        "unitPrice" => "25.00"
    ]
];

$commissions = $client->commission->list($items);
print_r($commissions);
```
```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Commission Object
                (
                    [ean] => 8712626055150
                    [condition] => NEW
                    [unitPrice] => 34.99
                    [fixedAmount] => 0.99
                    [percentage] => 15
                    [totalCost] => 6.24
                    [totalCostWithoutReduction] => 
                    [reductions] => Array
                        (
                        )

                )

            [1] => Budgetlens\BolRetailerApi\Resources\Commission Object
                (
                    [ean] => 8804269223123
                    [condition] => NEW
                    [unitPrice] => 699.95
                    [fixedAmount] => 0.99
                    [percentage] => 16
                    [totalCost] => 112.99
                    [totalCostWithoutReduction] => 
                    [reductions] => Array
                        (
                        )

                )

            [2] => Budgetlens\BolRetailerApi\Resources\Commission Object
                (
                    [ean] => 8712626055143
                    [condition] => GOOD
                    [unitPrice] => 24.5
                    [fixedAmount] => 0.99
                    [percentage] => 15
                    [totalCost] => 4.67
                    [totalCostWithoutReduction] => 
                    [reductions] => Array
                        (
                        )

                )

            [3] => Budgetlens\BolRetailerApi\Resources\Commission Object
                (
                    [ean] => 0604020064587
                    [condition] => NEW
                    [unitPrice] => 24.95
                    [fixedAmount] => 0.99
                    [percentage] => 15
                    [totalCost] => 4.73
                    [totalCostWithoutReduction] => 
                    [reductions] => Array
                        (
                        )

                )

            [4] => Budgetlens\BolRetailerApi\Resources\Commission Object
                (
                    [ean] => 8718526069334
                    [condition] => NEW
                    [unitPrice] => 25
                    [fixedAmount] => 0.99
                    [percentage] => 15
                    [totalCost] => 3.82
                    [totalCostWithoutReduction] => 4.74
                    [reductions] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Reduction Object
                                        (
                                            [maximumPrice] => 25.99
                                            [costReduction] => 0.92
                                            [startDate] => DateTime Object
                                                (
                                                    [date] => 2018-04-25 00:00:00.000000
                                                    [timezone_type] => 3
                                                    [timezone] => UTC
                                                )

                                            [endDate] => DateTime Object
                                                (
                                                    [date] => 2018-05-15 00:00:00.000000
                                                    [timezone_type] => 3
                                                    [timezone] => UTC
                                                )

                                        )

                                )

                        )

                )

        )

)
```
### Get all commissions and reductions by EAN per single EAN
```php
$ean = '8712626055143';
$unitPrice = 24.50;
$commission = $client->commission->get($ean, $unitPrice);
print_r($commission);
```
```php
Budgetlens\BolRetailerApi\Resources\Commission Object
(
    [ean] => 8712626055143
    [condition] => NEW
    [unitPrice] => 0.24
    [fixedAmount] => 0.99
    [percentage] => 15
    [totalCost] => 4.67
    [totalCostWithoutReduction] => 
    [reductions] => Array
        (
        )

)
```

## Insights

### Get offer insights
```php
$offerId = '7aec42a4-8c2b-4c38-ac3c-5e5a3f54341e';
$period = 'MONTH';
$numberOfPeriods = 1;
$name = ['PRODUCT_VISITS', 'BUY_BOX_PERCENTAGE'];

$insights = $client->insights->get($offerId, $period, $numberOfPeriods, $name);
print_r($insights);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Insight Object
                (
                    [name] => BUY_BOX_PERCENTAGE
                    [type] => percentage
                    [total] => 0
                    [countries] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                        (
                                            [countryCode] => BE
                                            [value] => 100
                                            [minimum] => 
                                            [maximum] => 
                                        )

                                    [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                        (
                                            [countryCode] => NL
                                            [value] => 92.9
                                            [minimum] => 
                                            [maximum] => 
                                        )

                                )

                        )

                    [periods] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Insights\Period Object
                                        (
                                            [day] => 0
                                            [week] => 0
                                            [month] => 12
                                            [year] => 2019
                                            [total] => 0
                                            [countries] => Illuminate\Support\Collection Object
                                                (
                                                    [items:protected] => Array
                                                        (
                                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                                (
                                                                    [countryCode] => BE
                                                                    [value] => 100
                                                                    [minimum] => 
                                                                    [maximum] => 
                                                                )

                                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                                (
                                                                    [countryCode] => NL
                                                                    [value] => 92.9
                                                                    [minimum] => 
                                                                    [maximum] => 
                                                                )

                                                        )

                                                )

                                        )

                                )

                        )

                )

            [1] => Budgetlens\BolRetailerApi\Resources\Insight Object
                (
                    [name] => PRODUCT_VISITS
                    [type] => count
                    [total] => 72
                    [countries] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                        (
                                            [countryCode] => BE
                                            [value] => 7
                                            [minimum] => 
                                            [maximum] => 
                                        )

                                    [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                        (
                                            [countryCode] => NL
                                            [value] => 65
                                            [minimum] => 
                                            [maximum] => 
                                        )

                                )

                        )

                    [periods] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Insights\Period Object
                                        (
                                            [day] => 0
                                            [week] => 0
                                            [month] => 12
                                            [year] => 2019
                                            [total] => 72
                                            [countries] => Illuminate\Support\Collection Object
                                                (
                                                    [items:protected] => Array
                                                        (
                                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                                (
                                                                    [countryCode] => BE
                                                                    [value] => 7
                                                                    [minimum] => 
                                                                    [maximum] => 
                                                                )

                                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                                (
                                                                    [countryCode] => NL
                                                                    [value] => 65
                                                                    [minimum] => 
                                                                    [maximum] => 
                                                                )

                                                        )

                                                )

                                        )

                                )

                        )

                )

        )
)
```

### Get performance indicators
```php
$year = 2019;
$week = 10;
$indicators = ['CANCELLATIONS', 'FULFILMENT', 'PHONE_AVAILABILITY', 'CASE_ITEM_RATIO',
    'TRACK_AND_TRACE', 'RETURNS', 'REVIEWS'
];

$indicators = $client->insights->getPerformanceIndicators($year, $week, $indicators);
print_r($indicators);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\PerformanceIndicator Object
                (
                    [name] => CANCELLATIONS
                    [type] => PERCENTAGE
                    [details] => Budgetlens\BolRetailerApi\Resources\Insights\PerformanceDetail Object
                        (
                            [week] => 10
                            [year] => 2019
                            [score] => Budgetlens\BolRetailerApi\Resources\Insights\Performance\Score Object
                                (
                                    [conforms] => 
                                    [numerator] => 8
                                    [denominator] => 88
                                    [value] => 0.88
                                    [distanceToNorm] => 0.86
                                )

                            [norm] => Budgetlens\BolRetailerApi\Resources\Insights\Performance\Norm Object
                                (
                                    [condition] => <=
                                    [value] => 0.02
                                )

                        )

                )

            [1] => Budgetlens\BolRetailerApi\Resources\PerformanceIndicator Object
                (
                    [name] => PHONE_AVAILABILITY
                    [type] => PERCENTAGE
                    [details] => Budgetlens\BolRetailerApi\Resources\Insights\PerformanceDetail Object
                        (
                            [week] => 10
                            [year] => 2019
                            [score] => 
                            [norm] => Budgetlens\BolRetailerApi\Resources\Insights\Performance\Norm Object
                                (
                                    [condition] => >=
                                    [value] => 0.9
                                )

                        )

                )

            [2] => Budgetlens\BolRetailerApi\Resources\PerformanceIndicator Object
                (
                    [name] => REVIEWS
                    [type] => AVERAGE
                    [details] => Budgetlens\BolRetailerApi\Resources\Insights\PerformanceDetail Object
                        (
                            [week] => 10
                            [year] => 2019
                            [score] => 
                            [norm] => Budgetlens\BolRetailerApi\Resources\Insights\Performance\Norm Object
                                (
                                    [condition] => >=
                                    [value] => 8
                                )

                        )

                )

            [3] => Budgetlens\BolRetailerApi\Resources\PerformanceIndicator Object
                (
                    [name] => CASE_ITEM_RATIO
                    [type] => PERCENTAGE
                    [details] => Budgetlens\BolRetailerApi\Resources\Insights\PerformanceDetail Object
                        (
                            [week] => 10
                            [year] => 2019
                            [score] => Budgetlens\BolRetailerApi\Resources\Insights\Performance\Score Object
                                (
                                    [conforms] => 1
                                    [numerator] => 0
                                    [denominator] => 54
                                    [value] => 0
                                    [distanceToNorm] => 0.05
                                )

                            [norm] => Budgetlens\BolRetailerApi\Resources\Insights\Performance\Norm Object
                                (
                                    [condition] => <=
                                    [value] => 0.05
                                )

                        )

                )

            [4] => Budgetlens\BolRetailerApi\Resources\PerformanceIndicator Object
                (
                    [name] => RETURNS
                    [type] => PERCENTAGE
                    [details] => Budgetlens\BolRetailerApi\Resources\Insights\PerformanceDetail Object
                        (
                            [week] => 10
                            [year] => 2019
                            [score] => 
                            [norm] => Budgetlens\BolRetailerApi\Resources\Insights\Performance\Norm Object
                                (
                                    [condition] => <=
                                    [value] => 0.05
                                )

                        )

                )

            [5] => Budgetlens\BolRetailerApi\Resources\PerformanceIndicator Object
                (
                    [name] => TRACK_AND_TRACE
                    [type] => PERCENTAGE
                    [details] => Budgetlens\BolRetailerApi\Resources\Insights\PerformanceDetail Object
                        (
                            [week] => 10
                            [year] => 2019
                            [score] => 
                            [norm] => Budgetlens\BolRetailerApi\Resources\Insights\Performance\Norm Object
                                (
                                    [condition] => >=
                                    [value] => 1
                                )

                        )

                )

            [6] => Budgetlens\BolRetailerApi\Resources\PerformanceIndicator Object
                (
                    [name] => FULFILMENT
                    [type] => PERCENTAGE
                    [details] => Budgetlens\BolRetailerApi\Resources\Insights\PerformanceDetail Object
                        (
                            [week] => 10
                            [year] => 2019
                            [score] => 
                            [norm] => Budgetlens\BolRetailerApi\Resources\Insights\Performance\Norm Object
                                (
                                    [condition] => >=
                                    [value] => 0.93
                                )

                        )

                )

        )

)
```

### Get sales forecast
```php
$offerId = '91c28f60-ed1d-4b85-e053-828b620a4ed5';
$weeks = 12;
$forecast = $client->insights->getSalesForecast($offerId, $weeks);
print_r($forecast);
```

```php
Budgetlens\BolRetailerApi\Resources\Insights\ForeCast Object
(
    [name] => SALES_FORECAST
    [type] => decimal
    [minimum] => 10
    [maximum] => 100
    [countries] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                        (
                            [countryCode] => BE
                            [value] => 
                            [minimum] => 0
                            [maximum] => 10
                        )

                    [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                        (
                            [countryCode] => NL
                            [value] => 
                            [minimum] => 10
                            [maximum] => 100
                        )

                )

        )

    [periods] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 1
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [1] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 2
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [2] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 3
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [3] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 4
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [4] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 5
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [5] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 6
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [6] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 7
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [7] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 8
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [8] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 9
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [9] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 10
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [10] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 11
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                    [11] => Budgetlens\BolRetailerApi\Resources\Insights\Forecast\Period Object
                        (
                            [weeksAhead] => 12
                            [minimum] => 0
                            [maximum] => 10
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 
                                                    [minimum] => 0
                                                    [maximum] => 10
                                                )

                                        )

                                )

                        )

                )

        )

)
```

### Get search terms (with related)
```php
$term = 'Potter';
$period = 'WEEK';
$weeks = 2;
$related = true;

$searchTerm = $client->insights->getSearchTerms($term, $period, $weeks, $related);
print_r($searchTerm);
```

```php
Budgetlens\BolRetailerApi\Resources\Insights\SearchTerm Object
(
    [searchTerm] => Potter
    [type] => count
    [total] => 19
    [countries] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                        (
                            [countryCode] => NL
                            [value] => 15
                            [minimum] => 
                            [maximum] => 
                        )

                    [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                        (
                            [countryCode] => BE
                            [value] => 4
                            [minimum] => 
                            [maximum] => 
                        )

                )

        )

    [periods] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Insights\Period Object
                        (
                            [day] => 0
                            [week] => 32
                            [month] => 0
                            [year] => 2021
                            [total] => 16
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 12
                                                    [minimum] => 
                                                    [maximum] => 
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 4
                                                    [minimum] => 
                                                    [maximum] => 
                                                )

                                        )

                                )

                        )

                    [1] => Budgetlens\BolRetailerApi\Resources\Insights\Period Object
                        (
                            [day] => 0
                            [week] => 33
                            [month] => 0
                            [year] => 2021
                            [total] => 3
                            [countries] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => NL
                                                    [value] => 3
                                                    [minimum] => 
                                                    [maximum] => 
                                                )

                                            [1] => Budgetlens\BolRetailerApi\Resources\Insights\Country Object
                                                (
                                                    [countryCode] => BE
                                                    [value] => 0
                                                    [minimum] => 
                                                    [maximum] => 
                                                )

                                        )

                                )

                        )

                )

        )

    [relatedSearchTerms] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 99
                            [searchTerm] => plotter
                        )

                    [1] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 13
                            [searchTerm] => pottery pots
                        )

                    [2] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 13
                            [searchTerm] => pottery
                        )

                    [3] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 7
                            [searchTerm] => pottery wheel
                        )

                    [4] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 5
                            [searchTerm] => potter lego
                        )

                    [5] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 4
                            [searchTerm] => potterdutch bestek
                        )

                    [6] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 3
                            [searchTerm] => potterbakker klei
                        )

                    [7] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 3
                            [searchTerm] => potters clay
                        )

                    [8] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 3
                            [searchTerm] => potter vuurbeker
                        )

                    [9] => Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm Object
                        (
                            [total] => 3
                            [searchTerm] => pottermore
                        )

                )

        )

)
```

## Inventory

### Get LVB/FBB inventory
```php
$inventory = $client->inventory->get();
print_r($inventory);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Inventory Object
                (
                    [ean] => 8718526069334
                    [bsku] => 2950003119187
                    [gradedStock] => 0
                    [regularStock] => 5
                    [title] => Star Wars - Nappy Star wars T-shirt - XL
                )

        )

)
```







## Orders

### List Orders

``` php
// all 
$orders = $client->orders->getOpenOrders();

// specific fulfilment
$orders = $client->orders->getOpenOrders('FBR');

// pagination
$orders = $client->orders->getOpenOrders('FBR', 2);
```

### Retrieve a single order
```php
$order = $client->orders->get($orderId);
```


### Ship Order Item With Transporter information
```php
$orderItemId = '6107434013';
$shipmentReference = 'unit-test';
$transport = new Transport([
    'transporterCode' => 'TNT',
    'trackAndTrace' => '3SAOLD1234567'
]);

$status = $client->orders->shipOrderItem($orderItemId, $shipmentReference, null, $transport);
```

### Ship Order Item using Bol Shipment Label
```php
$orderItemId = '6107434013';
$shipmentReference = 'unit-test';
$shipmentLabelId = 'd4c50077-0c19-435f-9bee-1b30b9f4ba1a';

$status = $client->orders->shipOrderItem($orderItemId, $shipmentReference, $shipmentLabelId);
```

### Cancel Order Item
```php
$orderItemId = '7616222250';
$status = $client->orders->cancelOrderItem($orderItemId, CancelReasonCodes::REQUESTED_BY_CUSTOMER);
```

---

## Offers
The offer related calls are async. Every action returns a `StatusProcess` resource. \
With this resource you can `poll` the status of the transaction.

**Note** After creating a new offer the `offerId` is set in the `entityId` property of the 
`StatusProcess` resource. (if state is `success`).

### Create FBB Offer
``` php
$offer = new Offer([
    'ean' => '0000007740404',
    'condition' => 'NEW',
    'reference' => 'unit-test',
    'onHoldByRetailer' => true,
    'unknownProductTitle' => 'unit-test',
    'price' => 99.99,
    'stock' => 0,
    'fulfilment' => 'FBB'
]);
$status = $client->offers->create($offer);
```

### Create FBR Offer
``` php
$offer = new Offer([
    'ean' => '0000007740404',
    'condition' => 'NEW',
    'reference' => 'unit-test',
    'onHoldByRetailer' => true,
    'unknownProductTitle' => 'unit-test',
    'price' => 99.99,
    'stock' => 0,
    'fulfilment' => new Fulfilment([
        'method' => 'FBR',
        'deliveryCode' => DeliveryCodes::WITHIN_24_HOURS_BEFORE_23
    ])
]);
$status = $client->offers->create($offer);

```

### Update Offer
```php
$offer = new Offer([
    'offerId' => 'offerId',
    'onHoldByRetailer' => false,
    'fulfilment' => 'FBR'
]);
$status = $client->offers->update($offer);
```

### Delete offer
```php
    $status = $client->offers->delete('$offerId');
```
Or you may pass the Offer instance directly to this method:
```php
    $offer = new Offer(['offerId' => '$offerId']);
    $status = $client->offers->delete($offer);
```

### Retrieve offer
```php
    $offer = $client->offers->get('$offerId');
```
Or you may pass the Offer instance directly to this method:
```php
    $offer = new Offer(['offerId' => '$offerId']);
    $offer = $client->offers->get($offer);
```

### Update offer pricing
```php
$offer = new Offer([
    'offerId' => '$offerId',
    'pricing' => new Pricing([
        'bundlePrices' => [
            ['quantity' => 1, 'unitPrice' => 99.99],
            ['quantity' => 2, 'unitPrice' => 89.99],
            ['quantity' => 3, 'unitPrice' => 85.99]
        ]
    ])
]);
$status = $client->offers->updatePrice($offer);
```

### Update Stock
```php
$offer = new Offer([
    'offerId' => '$offerId',
    'stock' => new Stock([
        'amount' => 100
    ])
]);
$status = $client->offers->updateStock($offer);
```

### Request Offers Export
The offers export comes with 2 steps.
- request the export
- retrieve the export

The request returns a status process to poll. Once the state is `success` the `entityId` contains the 
report-id to use for retrieving the export.

```php
$status = $client->offers->requestExport();
```

### Retrieve Offers Export
```php
$offers = $client->offers->getExport('$reportId');
```

### Request unpublished offers export
The unpublished offers export comes with 2 steps.
- request the export
- retrieve the export

The request returns a status process to poll. Once the state is `success` the `entityId` contains the
report-id to use for retrieving the export.

```php
$status = $client->offers->requestUnpublishedExport();
```

### Retrieve Unpublished Offers Export
```php
$offers = $client->offers->getUnpublishedExport('$reportId');
```

---

## Inventory

### Get LVB/FBB inventory
```php
$inventory = $client->inventory->get();
// filter inventory by quantity range. (0-20)
$inventory = $client->inventory->get('0-20');
```

## Invoices

### Get All Invoices
```php
$invoices = $client->invoices->list();
```

### Get Invoice 
```php
// format pdf
$invoicePdf = $client->invoices->get($invoiceId, 'pdf');
// -- save pdf 
$invoicePdf->save('path for storage');

// format xml
$invoiceXml = $client->invoices->get($invoiceId, 'xml');
// -- get xml
$invoiceXml->getXml();
```
--- 

## Shipping Labels

### Get Delivery Options
```php
$options = $client->shipping->getDeliveryOptions($orderResource);
```

### Create Shipping Label
```php
$order = new Order([
    'orderItems' => [
        [
            'orderItemId' => 2095052647
        ]
    ]
]);
$status = $client->shipping->createLabel($order, '$shippingLabelOfferId');
```

### Get Shipping Label
```php
$label = $this->client->shipping->getLabel('$shippingLabelId');
```

---

## Shipments

### Get Shipments List
```php
$shipments = $client->shipments->list();

// get FBR Shipments only
$shipments = $client->shipments->list('FBR');

// get Shipments Belonging to specific orderID
$shipments = $client->shipments->list(null, '$orderId');
```

### Get Shipment By ID
```php
$shipment = $this->client->shipments->get('$shipmentId');
```
---

## Inbounds
### Create Inbound
```php
$inbound = new Inbound([
    'reference' => 'my-reference',
    'timeSlot' => new Timeslot([
        'startDateTime' => new \DateTime('2018-04-05 12:00:00'),
        'endDateTime' => new \DateTime('2018-04-05 17:00:00')
    ]),
    'inboundTransporter' => new Transporter([
        'name' => 'PostNL',
        'code' => 'PostNL'
    ]),
    'labellingService' => false,
    'products' => [
        ['ean' => '8718526069331', 'announcedQuantity' => 1],
        ['ean' => '8718526069332', 'announcedQuantity' => 2],
        ['ean' => '8718526069333', 'announcedQuantity' => 3],
        ['ean' => '8718526069334', 'announcedQuantity' => 4]
    ]
]);
$status = $client->inbounds->create($inbound);
```

### List Inbounds
```php
$inbounds = $client->inbounds->list();
```

### Get Inbound Details
```php
$inbound = $client->inbounds->get($inboundId);
```

### Get Inbound Packing List
```php
$packing = $client->inbounds->getPackingList($inboundId);
// save pdf  (the filename is $inboundId.pdf)
$packing->save('$path');
// save pdf with custom filename
$packing->save('$path', 'my-filename.pdf');
```
### Get Inbound Shipping Label
```php
$label = $client->inbounds->getShippingLabel($inboundId);
// save pdf  (the filename is $inboundId.pdf)
$label->save('$path');
// save pdf with custom filename
$label->save('$path', 'my-shipping-label.pdf');
```
### Get Product Labels
```php
$products = [
    ['ean' => '0000000000000', 'quantity' => 1],
    ['ean' => '1111111111111', 'quantity' => 2]
];
$labels = $client->inbounds->getProductLabels($products, LabelFormat::ZEBRA_Z_PERFORM_1000T);
// save pdf  (the filename is product-labels.pdf)
$labels->save('$path');
// save pdf with custom filename
$labels->save('$path', 'my-product-labels.pdf');
```

### Get Delivery Windows
```php
$expectedDeliveryDate = new \DateTime();
$itemsToSend = 1;
$deliveryWindows = $client->inbounds->getDeliveryWindows($expectedDeliveryDate, $itemsToSend);
```

### Get Inbound transporters
```php
$transporters = $client->inbounds->getTransporters();
```
---

## Process Status

### Get Process Status By ID
``` php
$status = $client->status->get(1);
// or
$status = $client->status->getById(1);
```
Or you may pass the ProcessStatus instance directly to this method:
``` php
$statusResource = new ProcessStatus([
    'id' => 1
]);
$status = $client->status->get($statusResource);
```

### Get Process Status By Entity ID / Event TYPE
``` php
$statusResource = new ProcessStatus([
    'entityId' => 1,
    'eventType' => 'CONFIRM_SHIPMENT'
]);
$status = $client->status->get($statusResource);
// or 
$statusses = $client->status->getByEntityId(1, 'CONFIRM_SHIPMENT');
```

### Get Process Status and wait for it to complete
```php
$status = $this->client->status->waitUntilComplete($processId);

// try at least 10 times.
$status = $this->client->status->waitUntilComplete($processId, 10);

// try 10 times with a timeout of of 5 seconds.
$status = $this->client->status->waitUntilComplete($processId, 10, 5);
```

### Retrieve multiple statusses by id
``` php
$status = $client->status->batch([1,2,3]);
// or 
$batch = [
    new ProcessStatus(['id' => 1]),
    new ProcessStatus(['id' => 2]),
    new ProcessStatus(['id' => 3])
];
$statusses = $client->status->batch($batch);
```





## Usage with Laravel

You may incorporate this package in your Laravel application by using [this package](https://github.com/123lens/bol-laravel-client).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing


``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mark van den Broek][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-bol-retailer-version]: https://img.shields.io/badge/Retailer%20API%20Version-V5-blue
[ico-php-version]: https://img.shields.io/packagist/php-v/budgetlens/bol-retailer-api?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/budgetlens/bol-retailer-api.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-tests]: https://img.shields.io/github/workflow/status/budgetlens/bol-retailer-api/tests/main?label=tests&style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bol/bol-retailer-api.svg?style=flat-square
[ico-code-style]: https://styleci.io/repos/xxxx/shield?branch=main

[link-packagist]: https://packagist.org/packages/budgetlens/bol-retailer-api
[link-tests]: https://github.com/123lens/bol-retailer-api/actions/workflows/tests.yml?query=workflow%3Atests
[link-downloads]: https://packagist.org/packages/budgetlens/bol-retailer-api
[link-code-style]: https://styleci.io/repos/xxxx
