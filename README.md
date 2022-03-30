# Bol.com Retailer API client


![Bol API Retailer Version][ico-bol-retailer-version]
![PHP version][ico-php-version]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Tests][ico-tests]][link-tests]
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

## Invoices
> Not production ready !
 
### Get all invoices
```php
$invoices = $client->invoices->list();
print_r($invoices);
```

```php
Budgetlens\BolRetailerApi\Resources\Invoice Object
(
    [invoiceListItems] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\InvoiceItem Object
                        (
                            [invoiceId] => 4500022543921
                            [invoiceMediaTypes] => Array
                                (
                                    [0] => application/json
                                    [1] => application/xml
                                    [2] => application/pdf
                                )

                            [invoiceType] => ALL_IN_ONE
                            [issueDate] => DateTime Object
                                (
                                    [date] => 2018-04-30 22:00:00.000000
                                    [timezone_type] => 1
                                    [timezone] => +00:00
                                )

                            [invoicePeriod] => Array
                                (
                                    [endDate] => DateTime Object
                                        (
                                            [date] => 2019-04-29 22:00:00.000000
                                            [timezone_type] => 1
                                            [timezone] => +00:00
                                        )

                                    [startDate] => DateTime Object
                                        (
                                            [date] => 2019-03-31 22:00:00.000000
                                            [timezone_type] => 1
                                            [timezone] => +00:00
                                        )

                                )

                            [legalMonetaryTotal] => stdClass Object
                                (
                                    [lineExtensionAmount] => stdClass Object
                                        (
                                            [amount] => -1336.3
                                            [currencyID] => EUR
                                        )

                                    [payableAmount] => stdClass Object
                                        (
                                            [amount] => -1336.3
                                            [currencyID] => EUR
                                        )

                                    [taxExclusiveAmount] => stdClass Object
                                        (
                                            [amount] => -1290.66
                                            [currencyID] => EUR
                                        )

                                    [taxInclusiveAmount] => stdClass Object
                                        (
                                            [amount] => -1290.66
                                            [currencyID] => EUR
                                        )

                                )

                            [specificationMediaTypes] => Array
                                (
                                    [0] => application/json
                                    [1] => application/xml
                                    [2] => application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
                                )

                        )

                )

        )

    [period] => Array
        (
            [from] => DateTime Object
                (
                    [date] => 2018-04-12 00:00:00.000000
                    [timezone_type] => 1
                    [timezone] => +02:00
                )

            [till] => DateTime Object
                (
                    [date] => 2018-05-09 00:00:00.000000
                    [timezone_type] => 1
                    [timezone] => +02:00
                )

        )

    [type] => 
    [contents] => 
)
```

### Get an invoice by invoice id

### Get an invoice specification by invoice id


## Offers
The offer related calls are async. Every action returns a `StatusProcess` resource. \
With this resource you can `poll` the status of the transaction.

**Note** After creating a new offer the `offerId` is set in the `entityId` property of the
`StatusProcess` resource. (if state is `success`).

### Create a new offer
```php
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
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 
    [eventType] => CREATE_OFFER
    [description] => Create an offer with ean 0000007740404.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => 2021-03-17T11:00:31+01:00
    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

        )

    [id] => 1
)
```

### Request an offer export file
The offers export comes with 2 steps.
- request the export
- retrieve the export

The request returns a status process to poll. Once the state is `success` the `entityId` contains the
report-id to use for retrieving the export.

```php
$status = $client->offers->requestExport();
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 
    [eventType] => CREATE_OFFER_EXPORT
    [description] => Create an offer export.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => 2021-03-19T09:28:42+01:00
    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

        )

    [id] => 1
)
```

### Retrieve an offer export file by offer export id
```php
$offerExportId = 'some-id-retrieved-from-status-response-by-offer-export-request';
$offers = $client->offers->getExport($offerExportId);
print_r($offers);
```

```php
// todo
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




### Retrieve an offer by its offer id
```php
$offerId = '13722de8-8182-d161-5422-4a0a1caab5c8';
$offer = $client->offers->get($offerId);
print_r($offer);
```

// todo: check offer object response

```php
Budgetlens\BolRetailerApi\Resources\Offer Object
(
    [ean] => 3165140085229
    [offerId] => 13722de8-8182-d161-5422-4a0a1caab5c8
    [reference] => 02224499
    [condition] => Budgetlens\BolRetailerApi\Resources\Condition Object
        (
            [name] => NEW
            [category] => 
            [comment] => 
        )

    [onHoldByRetailer] => 
    [unknownProductTitle] => 
    [pricing] => Budgetlens\BolRetailerApi\Resources\Pricing Object
        (
            [bundlePrices] => Illuminate\Support\Collection Object
                (
                    [items:protected] => Array
                        (
                            [0] => Budgetlens\BolRetailerApi\Resources\Price Object
                                (
                                    [quantity] => 1
                                    [unitPrice] => 4499
                                )

                            [1] => Budgetlens\BolRetailerApi\Resources\Price Object
                                (
                                    [quantity] => 6
                                    [unitPrice] => 4299
                                )

                            [2] => Budgetlens\BolRetailerApi\Resources\Price Object
                                (
                                    [quantity] => 12
                                    [unitPrice] => 3999
                                )

                        )

                )

        )

    [stock] => Budgetlens\BolRetailerApi\Resources\Stock Object
        (
            [amount] => 3
            [managedByRetailer] => 
        )

    [fulfilment] => Budgetlens\BolRetailerApi\Resources\Fulfilment Object
        (
            [method] => FBR
            [deliveryCode] => 24uurs-15
            [distributionParty] => 
            [latestDeliveryDate] => 
            [expiryDate] => 
            [pickUpPoints] => 
        )

    [mutationDateTime] => 
    [store] => Budgetlens\BolRetailerApi\Resources\Store Object
        (
            [productTitle] => Bosch Waterpomp voor boormachine 2500 L/M
            [visible] => Array
                (
                    [0] => stdClass Object
                        (
                            [countryCode] => BE
                        )

                )

        )

    [notPublishableReasons] => 
)
```

### Update an offer
```php 
$offer = new Offer([
    'offerId' => '13722de8-8182-d161-5422-4a0a1caab5c8',
    'onHoldByRetailer' => false,
    'fulfilment' => 'FBR'
]);
$status = $client->offers->update($offer);
print_r($status);
```

```php
// todo: fix status response
```

### Delete offer by id

```php
$offerId = '13722de8-8182-d161-5422-4a0a1caab5c8';
$status = $this->client->offers->delete($offerId);
print_r($status);
```

```php
// todo: fix status response
```

### Update price(s) for offer by id

```php
$offer = new Offer([
    'offerId' => '13722de8-8182-d161-5422-4a0a1caab5c8',
    'pricing' => new Pricing([
        'bundlePrices' => [
            ['quantity' => 1, 'unitPrice' => 99.99],
            ['quantity' => 2, 'unitPrice' => 89.99],
            ['quantity' => 3, 'unitPrice' => 85.99]
        ]
    ])
]);
$status = $client->offers->updatePrice($offer);
print_r($status);
```

```php
// todo: fix status response
```

### Update stock for offer by id

```php
$offer = new Offer([
    'offerId' => '13722de8-8182-d161-5422-4a0a1caab5c8',
    'stock' => new Stock([
        'amount' => 100
    ])
]);
$status = $client->offers->updateStock($offer);
print_r($status);
```

```php
// todo: fix status response
```

## Orders

### Get Orders

``` php
// all 
$orders = $client->orders->getOpenOrders();

// specific fulfilment
$orders = $client->orders->getOpenOrders('FBR');
print_r($orders);
// pagination
$orders = $client->orders->getOpenOrders('FBR', 2);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Order Object
                (
                    [orderId] => 1043946570
                    [pickUpPoint] => 
                    [shipmentDetails] => 
                    [billingDetails] => 
                    [orderPlacedDateTime] => DateTime Object
                        (
                            [date] => 2019-04-29 16:18:21.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [orderItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\OrderItem Object
                                        (
                                            [orderItemId] => 6042823871
                                            [cancellationRequest] => 
                                            [fulfilment] => 
                                            [offer] => 
                                            [product] => 
                                            [ean] => 8785075035214
                                            [quantity] => 3
                                            [unitPrice] => 
                                            [commission] => 
                                        )

                                )

                        )

                )

            [1] => Budgetlens\BolRetailerApi\Resources\Order Object
                (
                    [orderId] => 1042831430
                    [pickUpPoint] => 
                    [shipmentDetails] => 
                    [billingDetails] => 
                    [orderPlacedDateTime] => DateTime Object
                        (
                            [date] => 2019-04-20 10:58:39.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [orderItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\OrderItem Object
                                        (
                                            [orderItemId] => 6107331382
                                            [cancellationRequest] => 
                                            [fulfilment] => 
                                            [offer] => 
                                            [product] => 
                                            [ean] => 8712626055143
                                            [quantity] => 1
                                            [unitPrice] => 
                                            [commission] => 
                                        )

                                    [1] => Budgetlens\BolRetailerApi\Resources\OrderItem Object
                                        (
                                            [orderItemId] => 6107331383
                                            [cancellationRequest] => 
                                            [fulfilment] => 
                                            [offer] => 
                                            [product] => 
                                            [ean] => 8804269223123
                                            [quantity] => 1
                                            [unitPrice] => 
                                            [commission] => 
                                        )

                                )

                        )

                )

        )

)
```

### Cancel an order item by an order item id
```php
$orderItemId = '7616222250';
$status = $client->orders->cancelOrderItem($orderItemId, CancelReasonCodes::REQUESTED_BY_CUSTOMER);
print_r($status);
```

```php
// todo: fix status response
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
print_r($status);
```

```php
// todo: fix status response
```


### Ship Order Item using Bol Shipment Label
```php
$orderItemId = '6107434013';
$shipmentReference = 'unit-test';
$shipmentLabelId = 'd4c50077-0c19-435f-9bee-1b30b9f4ba1a';

$status = $client->orders->shipOrderItem($orderItemId, $shipmentReference, $shipmentLabelId);
print_r($status);
```

```php
// todo: fix status response
```

### Retrieve a single order
```php
$order = $client->orders->get($orderId);
print_r($order);
```

```php
// todo: fix response
```


## Process Status

### Gets the status of an asynchronous process by entity id and event type for a retailer
``` php
$statusResource = new ProcessStatus([
    'entityId' => 1,
    'eventType' => 'CONFIRM_SHIPMENT'
]);
$status = $client->status->get($statusResource);
// or 
$status = $client->status->getByEntityId(1, 'CONFIRM_SHIPMENT');
print_r($status);
```

```php
// todo: fix response
```

### Gets the status of multiple asynchronous processes by an array of process status ids for a retailer

``` php
$status = $client->status->batch([1,2,3]);
// or 
$batch = [
    new ProcessStatus(['id' => 1]),
    new ProcessStatus(['id' => 2]),
    new ProcessStatus(['id' => 3])
];
$statusses = $client->status->batch($batch);
print_r($statusses);
```

```php
// todo: fix response
```

### Get the status of an asynchronous process by process status id
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
print_r($status);
```

```php
// todo: fix response
```

### Get Process Status and wait for it to complete
```php
$status = $this->client->status->waitUntilComplete($processId);

// try at least 10 times.
$status = $this->client->status->waitUntilComplete($processId, 10);

// try 10 times with a timeout of of 5 seconds.
$status = $this->client->status->waitUntilComplete($processId, 10, 5);

print_r($status);
```

```php
// todo: fix response
```


## Product Content
Not yet implemented

---

## Replenishments

### Get replenishments

```php
$replenishments = $client->replenishments->list(
    null,
    null,
    null,
    null,
    ['ANNOUNCED'],
);
print_r($replenishments);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Replenishment Object
                (
                    [replenishmentId] => 2312208180
                    [reference] => MYREF02
                    [creationDateTime] => DateTime Object
                        (
                            [date] => 2021-01-20 11:55:32.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [lines] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Replenishment\Line Object
                                        (
                                            [ean] => 0846127026185
                                            [type] => 
                                            [quantity] => 
                                        )

                                )

                        )

                    [invalidLines] => Array
                        (
                        )

                    [labelingByBol] => 
                    [state] => 
                    [deliveryInformation] => 
                    [numberOfLoadCarriers] => 
                    [loadCarriers] => Array
                        (
                        )

                    [stateTransitions] => Array
                        (
                        )

                )

            [1] => Budgetlens\BolRetailerApi\Resources\Replenishment Object
                (
                    [replenishmentId] => 2312208179
                    [reference] => MYREF01
                    [creationDateTime] => DateTime Object
                        (
                            [date] => 2021-01-20 11:35:33.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [lines] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Replenishment\Line Object
                                        (
                                            [ean] => 8716393000627
                                            [type] => 
                                            [quantity] => 
                                        )

                                    [1] => Budgetlens\BolRetailerApi\Resources\Replenishment\Line Object
                                        (
                                            [ean] => 0846127026185
                                            [type] => 
                                            [quantity] => 
                                        )

                                )

                        )

                    [invalidLines] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Replenishment\Line Object
                                        (
                                            [ean] => 
                                            [type] => UNKNOWN_FBB_PRODUCT
                                            [quantity] => 
                                        )

                                )

                        )

                    [labelingByBol] => 
                    [state] => 
                    [deliveryInformation] => 
                    [numberOfLoadCarriers] => 
                    [loadCarriers] => Array
                        (
                        )

                    [stateTransitions] => Array
                        (
                        )

                )

        )

)
```

### Post replenishment

```php
$replenishment = new Replenishment([
    'reference' => 'unittest001',
    'deliveryInformation' => new Replenishment\DeliveryInformation([
        'expectedDeliveryDate' => '2024-02-01',
        'transporterCode' => 'POSTNL'
    ]),
    'labelingByBol' => true,
    'numberOfLoadCarriers' => 2,
    'lines' => [
        [
            'ean' => '0846127026185',
            'quantity' => 5
        ],
        [
            'ean' => '8716393000627',
            'quantity' => 2
        ]
    ]
]);


$status = $client->replenishments->create($replenishment);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 
    [eventType] => CREATE_REPLENISHMENT
    [description] => Create replenishment with reference 'UNITTEST001'.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => 2021-08-19T12:07:09+02:00
    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

        )

)
```

### Post replenishment (Pickup)
```php
$replenishment = new Replenishment([
    'reference' => 'unittest002',
    'pickupAppointment' => new Replenishment\PickupAppointment([
        'address' => new Address([
            'streetName' => 'Utrechtseweg',
            'houseNumber' => 99,
            'zipCode' => '3702 AA',
            'city' => 'Zeist',
            'countryCode' => 'NL',
            'attentionOf' => 'Station'
        ]),
        'pickupTimeSlot' => new Replenishment\PickupTimeslot([
            'fromDateTime' => '2024-01-21 09:30:00',
            'untilDateTime' => '2024-01-21 11:30:00'
        ]),
        'commentToTransporter' => 'Custom reference'
    ]),
    'labelingByBol' => true,
    'numberOfLoadCarriers' => 1,
    'lines' => [
        [
            'ean' => '0846127026185',
            'quantity' => 1
        ]
    ]
]);
$status = $client->replenishments->create($replenishment);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 
    [eventType] => CREATE_REPLENISHMENT
    [description] => Create replenishment with reference 'UNITTEST002'.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => 2021-08-19T14:22:06+02:00
    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

        )

)
```
### Post pickup time slots

```php
$address = new Address([
    'streetName' => 'Utrechtseweg',
    'houseNumber' => 99,
    'houseNumberExtension' => 'A',
    'zipCode' => '3702 AA',
    'city' => 'Zeist',
    'countryCode' => 'NL',
]);
$numberOfLoadCarriers = 2;

$timeslots = $client->replenishments->pickupTimeslots($address, $numberOfLoadCarriers);
print_r($timeslots);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Replenishment\PickupTimeslot Object
                (
                    [fromDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 09:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [untilDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 11:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                )

            [1] => Budgetlens\BolRetailerApi\Resources\Replenishment\PickupTimeslot Object
                (
                    [fromDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 09:30:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [untilDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 11:30:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                )

            [2] => Budgetlens\BolRetailerApi\Resources\Replenishment\PickupTimeslot Object
                (
                    [fromDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 10:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [untilDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 12:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                )

            [3] => Budgetlens\BolRetailerApi\Resources\Replenishment\PickupTimeslot Object
                (
                    [fromDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 15:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [untilDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 17:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                )

            [4] => Budgetlens\BolRetailerApi\Resources\Replenishment\PickupTimeslot Object
                (
                    [fromDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 15:30:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [untilDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 17:30:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                )

            [5] => Budgetlens\BolRetailerApi\Resources\Replenishment\PickupTimeslot Object
                (
                    [fromDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 16:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [untilDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 18:00:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                )

            [6] => Budgetlens\BolRetailerApi\Resources\Replenishment\PickupTimeslot Object
                (
                    [fromDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 16:30:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [untilDateTime] => DateTime Object
                        (
                            [date] => 2024-01-21 18:30:00.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                )

        )

)
```

### Post product labels

```php
$products = [
    ['ean' => '0846127026185', 'quantity' => 5],
    ['ean' => '8716393000627', 'quantity' => 2]
];

$labels = $client->replenishments->productLabels($products, LabelFormat::AVERY_J8159);
print_r($labels);

// the labels can be saved by using resource
// filename is optional, if empty the product labels export id (product-labels) is used as filename
$labels->save($path, $filename); 
```

```php
.Budgetlens\BolRetailerApi\Resources\Replenishment\ProductLabels Object
(
    [id] => product-labels
    [contents] => PDFCONTENT
    [fileExt:protected] => pdf
)
```

### Get a replenishment by replenishment id

```php
$id = '2312208179';
$replenishment = $client->replenishments->get($id);
print_r($replenishment);
```

```php
Budgetlens\BolRetailerApi\Resources\Replenishment Object
(
    [replenishmentId] => 2312208179
    [reference] => MYREF01
    [creationDateTime] => DateTime Object
        (
            [date] => 2021-01-20 11:35:33.000000
            [timezone_type] => 1
            [timezone] => +01:00
        )

    [lines] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Replenishment\Line Object
                        (
                            [ean] => 8716393000627
                            [type] => 
                            [quantity] => 
                        )

                    [1] => Budgetlens\BolRetailerApi\Resources\Replenishment\Line Object
                        (
                            [ean] => 0846127026185
                            [type] => 
                            [quantity] => 
                        )

                )

        )

    [invalidLines] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Replenishment\Line Object
                        (
                            [ean] => 
                            [type] => UNKNOWN_FBB_PRODUCT
                            [quantity] => 
                        )

                )

        )

    [labelingByBol] => 1
    [state] => ANNOUNCED
    [deliveryInformation] => Budgetlens\BolRetailerApi\Resources\Replenishment\DeliveryInformation Object
        (
            [expectedDeliveryDate] => DateTime Object
                (
                    [date] => 2021-01-22 00:00:00.000000
                    [timezone_type] => 3
                    [timezone] => UTC
                )

            [transporterCode] => POSTNL
            [destinationWarehouse] => Budgetlens\BolRetailerApi\Resources\Replenishment\Warehouse Object
                (
                    [streetName] => Mechie Trommelenweg
                    [houseNumber] => 1
                    [zipCode] => 5145ND
                    [city] => Waalwijk
                    [countryCode] => NL
                    [attentionOf] => t.a.v. bol.com
                )

        )

    [numberOfLoadCarriers] => 2
    [loadCarriers] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Replenishment\LoadCarrier Object
                        (
                            [sscc] => 020001200000007628
                            [transportState] => ANNOUNCED
                            [transportStateUpdateDateTime] => DateTime Object
                                (
                                    [date] => 2021-01-20 11:35:34.000000
                                    [timezone_type] => 1
                                    [timezone] => +01:00
                                )

                        )

                    [1] => Budgetlens\BolRetailerApi\Resources\Replenishment\LoadCarrier Object
                        (
                            [sscc] => 020001200000007635
                            [transportState] => ANNOUNCED
                            [transportStateUpdateDateTime] => DateTime Object
                                (
                                    [date] => 2021-01-20 11:35:34.000000
                                    [timezone_type] => 1
                                    [timezone] => +01:00
                                )

                        )

                )

        )

    [stateTransitions] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Replenishment\StateTransition Object
                        (
                            [state] => ANNOUNCED
                            [stateDateTime] => DateTime Object
                                (
                                    [date] => 2021-01-20 11:35:34.000000
                                    [timezone_type] => 1
                                    [timezone] => +01:00
                                )

                        )

                )

        )

)
```

### Update replenishment

```php
$replenishment = new Replenishment([
    'replenishmentId' => '2312188192',
    'deliveryInformation' => new Replenishment\DeliveryInformation([
        'expectedDeliveryDate' => '2024-01-29'
    ])
]);
$status = $client->replenishments->update($replenishment);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 2312188192
    [eventType] => UPDATE_REPLENISHMENT
    [description] => Update replenishment with replenishment id '2312188192'.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => 2021-08-19T15:13:28+02:00
    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

        )

)
```

### Get load carrier labels

```php
$id = '4220489554';
$labelType = 'TRANSPORT';
$label = $client->replenishments->loadCarrierLabels($id, $labelType);
```

```php
// todo: implement response
```

### Get pick list

```php
$id = '2312208179';
$response = $client->replenishments->picklist($id);
print_r($response);
// the picklist can be saved by using resource
// filename is optional, if empty the picklist id is used as filename
$response->save($path, $filename); 
```

```php
Budgetlens\BolRetailerApi\Resources\Replenishment\Picklist Object
(
    [id] => 2312208179
    [contents] => PDFCONTENT
    [fileExt:protected] => pdf
)
```

--- 

## Returns

### Get All unhandled FBB Returns

```php
$returns = $client->returns->list('FBB', false);
print_r($returns);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Returns Object
                (
                    [returnId] => 6
                    [registrationDateTime] => DateTime Object
                        (
                            [date] => 2018-04-27 19:55:12.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [fulfilmentMethod] => FBB
                    [returnItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Returns\Item Object
                                        (
                                            [rmaId] => 86127131
                                            [orderId] => 7616247328
                                            [ean] => 8718526069334
                                            [title] => 
                                            [expectedQuantity] => 1
                                            [returnReason] => Budgetlens\BolRetailerApi\Resources\Returns\ReturnReason Object
                                                (
                                                    [mainReason] => Verkeerd besteld
                                                    [detailedReason] => 
                                                    [customerComments] => Ik wilde eigenlijk een groter formaat
                                                )

                                            [trackAndTrace] => 
                                            [transporterName] => 
                                            [handled] => 
                                            [processingResults] => 
                                            [customerDetails] => 
                                        )

                                )

                        )

                )

        )

)
```

### Get All Handled Returns
```php
$returns = $client->returns->list(null, true);
print_r($returns);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Returns Object
                (
                    [returnId] => 15897410
                    [registrationDateTime] => DateTime Object
                        (
                            [date] => 2019-06-03 17:06:12.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [fulfilmentMethod] => FBR
                    [returnItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Returns\Item Object
                                        (
                                            [rmaId] => 60283607
                                            [orderId] => 1044796550
                                            [ean] => 0634154562079
                                            [title] => 
                                            [expectedQuantity] => 1
                                            [returnReason] => Budgetlens\BolRetailerApi\Resources\Returns\ReturnReason Object
                                                (
                                                    [mainReason] => Verkeerde maat of formaat
                                                    [detailedReason] => 
                                                    [customerComments] => Verkeerde maat of formaat
                                                )

                                            [trackAndTrace] => 
                                            [transporterName] => 
                                            [handled] => 1
                                            [processingResults] => 
                                            [customerDetails] => 
                                        )

                                )

                        )

                )

            [1] => Budgetlens\BolRetailerApi\Resources\Returns Object
                (
                    [returnId] => 15896813
                    [registrationDateTime] => DateTime Object
                        (
                            [date] => 2019-05-09 13:04:22.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [fulfilmentMethod] => FBR
                    [returnItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Returns\Item Object
                                        (
                                            [rmaId] => 60282944
                                            [orderId] => 1043965710
                                            [ean] => 0811571016532
                                            [title] => 
                                            [expectedQuantity] => 1
                                            [returnReason] => Budgetlens\BolRetailerApi\Resources\Returns\ReturnReason Object
                                                (
                                                    [mainReason] => Verkeerd artikel ontvangen
                                                    [detailedReason] => 
                                                    [customerComments] => Verkeerd artikel ontvangen
                                                )

                                            [trackAndTrace] => 
                                            [transporterName] => 
                                            [handled] => 1
                                            [processingResults] => 
                                            [customerDetails] => 
                                        )

                                    [1] => Budgetlens\BolRetailerApi\Resources\Returns\Item Object
                                        (
                                            [rmaId] => 60282945
                                            [orderId] => 1044194100
                                            [ean] => 3138520283072
                                            [title] => 
                                            [expectedQuantity] => 1
                                            [returnReason] => Budgetlens\BolRetailerApi\Resources\Returns\ReturnReason Object
                                                (
                                                    [mainReason] => Artikel is defect/werkt niet
                                                    [detailedReason] => 
                                                    [customerComments] => Artikel is defect/werkt niet
                                                )

                                            [trackAndTrace] => 
                                            [transporterName] => 
                                            [handled] => 1
                                            [processingResults] => 
                                            [customerDetails] => 
                                        )

                                )

                        )

                )

            [2] => Budgetlens\BolRetailerApi\Resources\Returns Object
                (
                    [returnId] => 15892026
                    [registrationDateTime] => DateTime Object
                        (
                            [date] => 2018-11-06 10:48:34.000000
                            [timezone_type] => 1
                            [timezone] => +01:00
                        )

                    [fulfilmentMethod] => FBR
                    [returnItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Returns\Item Object
                                        (
                                            [rmaId] => 60278123
                                            [orderId] => 1020824520
                                            [ean] => 5702015866736
                                            [title] => 
                                            [expectedQuantity] => 1
                                            [returnReason] => Budgetlens\BolRetailerApi\Resources\Returns\ReturnReason Object
                                                (
                                                    [mainReason] => Geen reden
                                                    [detailedReason] => 
                                                    [customerComments] => Geen reden
                                                )

                                            [trackAndTrace] => 
                                            [transporterName] => 
                                            [handled] => 1
                                            [processingResults] => 
                                            [customerDetails] => 
                                        )

                                )

                        )

                )

        )

)
```

### Create return

```php
$orderItemId = '1044796550';
$quantity = 1;
$state = ReturnResultTypes::RETURN_RECEIVED;

$status = $client->returns->create($orderItemId, $quantity, $state);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 
    [eventType] => CREATE_RETURN_ITEM
    [description] => Create return for order item 1044796550.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => 2021-08-24T10:20:09+02:00
    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

        )

)
```

### Get a return by return id

```php
$id = 15896813;
$return = $client->returns->get($id);
print_r($return);
```

```php
Budgetlens\BolRetailerApi\Resources\Returns Object
(
    [returnId] => 15896813
    [registrationDateTime] => DateTime Object
        (
            [date] => 2019-05-09 13:04:22.000000
            [timezone_type] => 1
            [timezone] => +02:00
        )

    [fulfilmentMethod] => FBR
    [returnItems] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Returns\Item Object
                        (
                            [rmaId] => 60282945
                            [orderId] => 1044194100
                            [ean] => 3138520283072
                            [title] => Campingaz Cv470 Plus - Easy clic
                            [expectedQuantity] => 1
                            [returnReason] => Budgetlens\BolRetailerApi\Resources\Returns\ReturnReason Object
                                (
                                    [mainReason] => Artikel is defect/werkt niet
                                    [detailedReason] => 
                                    [customerComments] => Andere verwachting
                                )

                            [trackAndTrace] => 3SBLCR954606709
                            [transporterName] => PostNL
                            [handled] => 1
                            [processingResults] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Returns\ProcessingResult Object
                                                (
                                                    [quantity] => 1
                                                    [processingResult] => CANCELLED
                                                    [handlingResult] => EXPIRED
                                                    [processingDateTime] => DateTime Object
                                                        (
                                                            [date] => 2019-05-30 13:06:03.000000
                                                            [timezone_type] => 1
                                                            [timezone] => +02:00
                                                        )

                                                )

                                        )

                                )

                            [customerDetails] => Budgetlens\BolRetailerApi\Resources\Address Object
                                (
                                    [salutation] => 
                                    [firstName] => Luke
                                    [surname] => Skywalker
                                    [streetName] => Acmestraat
                                    [houseNumber] => 1
                                    [houseNumberExtension] => 
                                    [extraAddressInformation] => 
                                    [zipCode] => 1234AB
                                    [city] => Acme City
                                    [countryCode] => NL
                                    [email] => 2wb3y4vqq667avck657zbhx2uevm7a@verkopen.test2.bol.com
                                    [language] => 
                                    [company] => Company Corp
                                    [vatNumber] => 
                                    [kvkNumber] => 
                                    [orderReference] => 
                                    [attentionOf] => 
                                )

                        )

                    [1] => Budgetlens\BolRetailerApi\Resources\Returns\Item Object
                        (
                            [rmaId] => 60282944
                            [orderId] => 1043965710
                            [ean] => 0811571016532
                            [title] => Google Chromecast 2
                            [expectedQuantity] => 1
                            [returnReason] => Budgetlens\BolRetailerApi\Resources\Returns\ReturnReason Object
                                (
                                    [mainReason] => Verkeerd artikel ontvangen
                                    [detailedReason] => 
                                    [customerComments] => 
                                )

                            [trackAndTrace] => 12ABCD345678900
                            [transporterName] => PostNL
                            [handled] => 1
                            [processingResults] => Illuminate\Support\Collection Object
                                (
                                    [items:protected] => Array
                                        (
                                            [0] => Budgetlens\BolRetailerApi\Resources\Returns\ProcessingResult Object
                                                (
                                                    [quantity] => 1
                                                    [processingResult] => ACCEPTED
                                                    [handlingResult] => RETURN_RECEIVED
                                                    [processingDateTime] => DateTime Object
                                                        (
                                                            [date] => 2019-05-09 13:05:09.000000
                                                            [timezone_type] => 1
                                                            [timezone] => +02:00
                                                        )

                                                )

                                        )

                                )

                            [customerDetails] => Budgetlens\BolRetailerApi\Resources\Address Object
                                (
                                    [salutation] => 
                                    [firstName] => 
                                    [surname] => 
                                    [streetName] => 
                                    [houseNumber] => 
                                    [houseNumberExtension] => 
                                    [extraAddressInformation] => 
                                    [zipCode] => 1234AB
                                    [city] => 
                                    [countryCode] => NL
                                    [email] => 2wb3y4vqq667avck657zbhx2uevm7a@verkopen.test2.bol.com
                                    [language] => 
                                    [company] => 
                                    [vatNumber] => 
                                    [kvkNumber] => 
                                    [orderReference] => 
                                    [attentionOf] => 
                                )

                        )

                )

        )

)
```

### Handle a return

```php
$rmaId = '86123452';
$state = ReturnResultTypes::RETURN_RECEIVED;
$quantity = 3;

$status = $client->returns->handle($rmaId, $quantity, $state);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 86123452
    [eventType] => HANDLE_RETURN_ITEM
    [description] => Handle the return item with return number 86123452.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => 2021-08-24T10:35:22+02:00
    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

        )

)
```

--- 

## Shipments

### Get Shipments List
```php
$shipments = $client->shipments->list();
print_r($shipments);

// get FBR Shipments only
$shipments = $client->shipments->list('FBR');

// get Shipments Belonging to specific orderID
$shipments = $client->shipments->list(null, '$orderId');
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Shipment Object
                (
                    [shipmentId] => 914587795
                    [shipmentDateTime] => DateTime Object
                        (
                            [date] => 2018-04-20 19:20:11.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [shipmentReference] => 
                    [pickUpPoint] => 
                    [order] => Budgetlens\BolRetailerApi\Resources\Order Object
                        (
                            [orderId] => 7616222250
                            [pickUpPoint] => 
                            [shipmentDetails] => 
                            [billingDetails] => 
                            [orderPlacedDateTime] => DateTime Object
                                (
                                    [date] => 2018-01-08 10:45:12.000000
                                    [timezone_type] => 1
                                    [timezone] => +01:00
                                )

                            [orderItems] => Array
                                (
                                )

                        )

                    [shipmentDetails] => 
                    [billingDetails] => 
                    [shipmentItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Shipment\ShipmentItem Object
                                        (
                                            [orderItemId] => 6107434013
                                            [ean] => 8421152081990
                                            [fulfilment] => 
                                            [offer] => 
                                            [product] => 
                                            [quantity] => 
                                            [unitPrice] => 
                                            [commission] => 
                                        )

                                )

                        )

                    [transport] => Budgetlens\BolRetailerApi\Resources\Transport Object
                        (
                            [transportId] => 358612589
                            [transporterCode] => 
                            [trackAndTrace] => 
                        )

                )

            [1] => Budgetlens\BolRetailerApi\Resources\Shipment Object
                (
                    [shipmentId] => 953266576
                    [shipmentDateTime] => DateTime Object
                        (
                            [date] => 2018-04-20 19:10:19.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [shipmentReference] => 
                    [pickUpPoint] => 
                    [order] => Budgetlens\BolRetailerApi\Resources\Order Object
                        (
                            [orderId] => 7616222700
                            [pickUpPoint] => 
                            [shipmentDetails] => 
                            [billingDetails] => 
                            [orderPlacedDateTime] => DateTime Object
                                (
                                    [date] => 2018-01-08 10:45:12.000000
                                    [timezone_type] => 1
                                    [timezone] => +01:00
                                )

                            [orderItems] => Array
                                (
                                )

                        )

                    [shipmentDetails] => 
                    [billingDetails] => 
                    [shipmentItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Shipment\ShipmentItem Object
                                        (
                                            [orderItemId] => 6107331383
                                            [ean] => 8421152081990
                                            [fulfilment] => 
                                            [offer] => 
                                            [product] => 
                                            [quantity] => 
                                            [unitPrice] => 
                                            [commission] => 
                                        )

                                )

                        )

                    [transport] => Budgetlens\BolRetailerApi\Resources\Transport Object
                        (
                            [transportId] => 356988715
                            [transporterCode] => 
                            [trackAndTrace] => 
                        )

                )

            [2] => Budgetlens\BolRetailerApi\Resources\Shipment Object
                (
                    [shipmentId] => 953267579
                    [shipmentDateTime] => DateTime Object
                        (
                            [date] => 2018-04-20 18:46:01.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [shipmentReference] => 
                    [pickUpPoint] => 
                    [order] => Budgetlens\BolRetailerApi\Resources\Order Object
                        (
                            [orderId] => 7616222700
                            [pickUpPoint] => 
                            [shipmentDetails] => 
                            [billingDetails] => 
                            [orderPlacedDateTime] => DateTime Object
                                (
                                    [date] => 2018-01-08 10:45:12.000000
                                    [timezone_type] => 1
                                    [timezone] => +01:00
                                )

                            [orderItems] => Array
                                (
                                )

                        )

                    [shipmentDetails] => 
                    [billingDetails] => 
                    [shipmentItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Shipment\ShipmentItem Object
                                        (
                                            [orderItemId] => 6107432387
                                            [ean] => 8421152081990
                                            [fulfilment] => 
                                            [offer] => 
                                            [product] => 
                                            [quantity] => 
                                            [unitPrice] => 
                                            [commission] => 
                                        )

                                )

                        )

                    [transport] => Budgetlens\BolRetailerApi\Resources\Transport Object
                        (
                            [transportId] => 356988715
                            [transporterCode] => 
                            [trackAndTrace] => 
                        )

                )

            [3] => Budgetlens\BolRetailerApi\Resources\Shipment Object
                (
                    [shipmentId] => 953316694
                    [shipmentDateTime] => DateTime Object
                        (
                            [date] => 2019-07-04 18:16:28.000000
                            [timezone_type] => 1
                            [timezone] => +02:00
                        )

                    [shipmentReference] => WSR1096399593
                    [pickUpPoint] => 
                    [order] => Budgetlens\BolRetailerApi\Resources\Order Object
                        (
                            [orderId] => 4616526971
                            [pickUpPoint] => 
                            [shipmentDetails] => 
                            [billingDetails] => 
                            [orderPlacedDateTime] => DateTime Object
                                (
                                    [date] => 2018-01-08 10:45:12.000000
                                    [timezone_type] => 1
                                    [timezone] => +01:00
                                )

                            [orderItems] => Array
                                (
                                )

                        )

                    [shipmentDetails] => 
                    [billingDetails] => 
                    [shipmentItems] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => Budgetlens\BolRetailerApi\Resources\Shipment\ShipmentItem Object
                                        (
                                            [orderItemId] => 6702312887
                                            [ean] => 8421152081990
                                            [fulfilment] => 
                                            [offer] => 
                                            [product] => 
                                            [quantity] => 
                                            [unitPrice] => 
                                            [commission] => 
                                        )

                                )

                        )

                    [transport] => Budgetlens\BolRetailerApi\Resources\Transport Object
                        (
                            [transportId] => 356567193
                            [transporterCode] => 
                            [trackAndTrace] => 
                        )

                )

        )

)
```

### Get Shipment By ID
```php
$id = '914587795';
$shipment = $client->shipments->get($id);
print_r($shipment);
```

```php
Budgetlens\BolRetailerApi\Resources\Shipment Object
(
    [shipmentId] => 914587795
    [shipmentDateTime] => DateTime Object
        (
            [date] => 2018-04-20 19:20:11.000000
            [timezone_type] => 1
            [timezone] => +02:00
        )

    [shipmentReference] => Shipment1
    [pickUpPoint] => 1
    [order] => Budgetlens\BolRetailerApi\Resources\Order Object
        (
            [orderId] => 7616222250
            [pickUpPoint] => 
            [shipmentDetails] => 
            [billingDetails] => 
            [orderPlacedDateTime] => DateTime Object
                (
                    [date] => 2018-04-20 16:13:31.000000
                    [timezone_type] => 1
                    [timezone] => +02:00
                )

            [orderItems] => Array
                (
                )

        )

    [shipmentDetails] => Budgetlens\BolRetailerApi\Resources\Address Object
        (
            [salutation] => MALE
            [firstName] => Anakin
            [surname] => Skywalker
            [streetName] => Tatooinestraat
            [houseNumber] => 100
            [houseNumberExtension] => B
            [extraAddressInformation] => 
            [zipCode] => 3528BJ
            [city] => Utrecht
            [countryCode] => NL
            [email] => 25whxgzlkmvotjhskwf5x27wlrldny@verkopen.test2.bol.com
            [language] => nl
            [company] => 
            [vatNumber] => 
            [kvkNumber] => 
            [orderReference] => 
            [attentionOf] => 
        )

    [billingDetails] => Budgetlens\BolRetailerApi\Resources\Address Object
        (
            [salutation] => MALE
            [firstName] => Anakin
            [surname] => Skywalker
            [streetName] => Tatooinestraat
            [houseNumber] => 100
            [houseNumberExtension] => B
            [extraAddressInformation] => Extra informatie
            [zipCode] => 3528BJ
            [city] => UTRECHT
            [countryCode] => NL
            [email] => 25whxgzlkmvotjhskwf5x27wlrldny@verkopen.test2.bol.com
            [language] => 
            [company] => Pieter Post
            [vatNumber] => NL123456789B01
            [kvkNumber] => 99887766
            [orderReference] => Mijn order ref
            [attentionOf] => 
        )

    [shipmentItems] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\Shipment\ShipmentItem Object
                        (
                            [orderItemId] => 6107434013
                            [ean] => 
                            [fulfilment] => Budgetlens\BolRetailerApi\Resources\Fulfilment Object
                                (
                                    [method] => FBR
                                    [deliveryCode] => 24uurs-14
                                    [distributionParty] => 
                                    [latestDeliveryDate] => 2018-04-21
                                    [expiryDate] => 
                                    [pickUpPoints] => 
                                )

                            [offer] => Budgetlens\BolRetailerApi\Resources\Offer Object
                                (
                                    [ean] => 
                                    [offerId] => 8f6085e3-de98-c97f-e053-3542090a63b3
                                    [reference] => Test1
                                    [condition] => Budgetlens\BolRetailerApi\Resources\Condition Object
                                        (
                                            [name] => 
                                            [category] => 
                                            [comment] => 
                                        )

                                    [onHoldByRetailer] => 
                                    [unknownProductTitle] => 
                                    [pricing] => Budgetlens\BolRetailerApi\Resources\Pricing Object
                                        (
                                            [bundlePrices] => Array
                                                (
                                                )

                                        )

                                    [stock] => Budgetlens\BolRetailerApi\Resources\Stock Object
                                        (
                                            [amount] => 0
                                            [managedByRetailer] => 
                                        )

                                    [fulfilment] => Budgetlens\BolRetailerApi\Resources\Fulfilment Object
                                        (
                                            [method] => 
                                            [deliveryCode] => 24uurs-14
                                            [distributionParty] => 
                                            [latestDeliveryDate] => 
                                            [expiryDate] => 
                                            [pickUpPoints] => 
                                        )

                                    [mutationDateTime] => 
                                    [store] => 
                                    [notPublishableReasons] => 
                                )

                            [product] => Budgetlens\BolRetailerApi\Resources\Product Object
                                (
                                    [ean] => 8712626055150
                                    [title] => Star Wars - Original Trilogy
                                    [announcedQuantity] => 
                                )

                            [quantity] => 3
                            [unitPrice] => 34.99
                            [commission] => 14
                        )

                )

        )

    [transport] => Budgetlens\BolRetailerApi\Resources\Transport Object
        (
            [transportId] => 358612589
            [transporterCode] => TNT
            [trackAndTrace] => 3SAOLD1234567
        )

)
```

---

## Shipping Labels

### Create Shipping Label
```php
$order = new Order([
    'orderItems' => [
        [
            'orderItemId' => 2095052647
        ]
    ]
]);
$shippingLabelOfferId = '8f956bfc-fabe-45b4-b0e1-1b52a0896b74';
$status = $client->shipping->createLabel($order, $shippingLabelOfferId);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 
    [eventType] => CREATE_SHIPPING_LABEL
    [description] => Create shipping label with shipping label offer id 8f956bfc-fabe-45b4-b0e1-1b52a0896b74.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => 2021-03-22T09:06:13+01:00
    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

        )

    [id] => 1
)
```

### Get delivery options for a shippable configuration of a number of order items within an order.

```php
$order = new Order([
    'orderItems' => [
        [
            'orderItemId' => 2095052647
        ]
    ]
]);

$options = $client->shipping->getDeliveryOptions($order);
print_r($options);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\DeliveryOption Object
                (
                    [shippingLabelOfferId] => 32c4a88c-3c64-41ad-83a1-24450b341747
                    [validUntilDate] => 2020-10-17
                    [transporterCode] => TNT
                    [labelType] => PARCEL
                    [labelPrice] => Budgetlens\BolRetailerApi\Resources\LabelPrice Object
                        (
                            [totalPrice] => 441
                        )

                    [packageRestrictions] => Budgetlens\BolRetailerApi\Resources\PackageRestriction Object
                        (
                            [maxWeight] => 10 kg
                            [maxDimensions] => 100 x 50 x 50 cm
                        )

                    [handoverDetails] => Budgetlens\BolRetailerApi\Resources\HandoverDetails Object
                        (
                            [meetsCustomerExpectation] => 1
                            [latestHandoverDateTime] => DateTime Object
                                (
                                    [date] => 2020-10-03 18:00:00.000000
                                    [timezone_type] => 1
                                    [timezone] => +02:00
                                )

                            [collectionMethod] => DROP_OFF
                        )

                )

            [1] => Budgetlens\BolRetailerApi\Resources\DeliveryOption Object
                (
                    [shippingLabelOfferId] => fb7f20c0-d847-4d62-9c57-7aba64fb9b52
                    [validUntilDate] => 2020-10-08
                    [transporterCode] => TNT_BRIEF
                    [labelType] => MAILBOX_LIGHT
                    [labelPrice] => Budgetlens\BolRetailerApi\Resources\LabelPrice Object
                        (
                            [totalPrice] => 288
                        )

                    [packageRestrictions] => Budgetlens\BolRetailerApi\Resources\PackageRestriction Object
                        (
                            [maxWeight] => 2 kg
                            [maxDimensions] => 38 x 26,5 x 3,2 cm
                        )

                    [handoverDetails] => Budgetlens\BolRetailerApi\Resources\HandoverDetails Object
                        (
                            [meetsCustomerExpectation] => 1
                            [latestHandoverDateTime] => DateTime Object
                                (
                                    [date] => 2020-09-24 18:30:00.000000
                                    [timezone_type] => 1
                                    [timezone] => +02:00
                                )

                            [collectionMethod] => DROP_OFF
                        )

                )

        )

)
```

### Get a shipping label
```php
$id = 'c628ba4f-f31a-4fac-a6a0-062326d0dbbd';
$label = $client->shipping->getLabel($id);
print_r($label);
```

```php
Budgetlens\BolRetailerApi\Resources\Label Object
(
    [id] => c628ba4f-f31a-4fac-a6a0-062326d0dbbd
    [contents] => PDFCONTENT
    [fileExt:protected] => pdf
)
```

---

## Transports

### Add transport information by transport id

```php
$transportId = '358612589';
$transporterCode = TransporterCode::TNT;
$trackAndTrace = '3SAOLD1234567';

$status = $client->transports->addInformation($transportId, $transporterCode, $trackAndTrace);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 358612589
    [eventType] => CHANGE_TRANSPORT
    [description] => Change transport with id 358612589.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => 2021-08-24T15:14:40+02:00
    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

        )

)
```
---

## Subscriptions

### List subscriptions
```php
$subscriptions = $this->client->subscriptions->list();
print_r($subscriptions);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Subscription Object
                (
                    [id] => 1234
                    [resources] => Illuminate\Support\Collection Object
                        (
                            [items:protected] => Array
                                (
                                    [0] => PROCESS_STATUS
                                )

                            [escapeWhenCastingToString:protected] => 
                        )

                    [url] => https://www.example.com/push
                )

        )

    [escapeWhenCastingToString:protected] => 
)
```

### Get Subscription By Id
```php
$id = '1234';
$subscription = $this->client->subscriptions->get($id);
print_r($subscription)
```

```php
Budgetlens\BolRetailerApi\Resources\Subscription Object
(
    [id] => 1234
    [resources] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => PROCESS_STATUS
                )

            [escapeWhenCastingToString:protected] => 
        )

    [url] => https://www.example.com/push
)
```

### Create subscription
```php
$resources = [SubscriptionResource::PROCESS_STATUS];
$url = 'https://www.example.com/push';
$status = $this->client->subscriptions->create($resources, $url);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 
    [eventType] => CREATE_SUBSCRIPTION
    [description] => Create push notification subscription.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => DateTime Object
        (
            [date] => 2022-02-16 22:27:37.000000
            [timezone_type] => 1
            [timezone] => +01:00
        )

    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

            [escapeWhenCastingToString:protected] => 
        )

)
```

### Update subscription
```php
$id = '1234';
$resources = [SubscriptionResource::PROCESS_STATUS];
$url = 'https://www.example.com/push';

$status = $this->client->subscriptions->update($id, $resources, $url);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 1234
    [eventType] => UPDATE_SUBSCRIPTION
    [description] => Update push notification subscription with id 1234.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => DateTime Object
        (
            [date] => 2022-02-16 22:28:27.000000
            [timezone_type] => 1
            [timezone] => +01:00
        )

    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

            [escapeWhenCastingToString:protected] => 
        )

)
```

### Delete subscription
```php
$id = '1234';
$status = $this->client->subscriptions->delete($id);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 
    [eventType] => DELETE_SUBSCRIPTION
    [description] => Delete push notification subscription with id 1234.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => DateTime Object
        (
            [date] => 2022-02-16 22:29:15.000000
            [timezone_type] => 1
            [timezone] => +01:00
        )

    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

            [escapeWhenCastingToString:protected] => 
        )

)
```

### Send test notification
```php
$id = '54321';
$status = $this->client->subscriptions->test($id);
print_r($status);
```

```php
Budgetlens\BolRetailerApi\Resources\ProcessStatus Object
(
    [processStatusId] => 1
    [entityId] => 
    [eventType] => SEND_SUBSCRIPTION_TST_MSG
    [description] => Send a test push notification for configured subscription.
    [status] => PENDING
    [errorMessage] => 
    [createTimestamp] => DateTime Object
        (
            [date] => 2022-02-16 22:30:15.000000
            [timezone_type] => 1
            [timezone] => +01:00
        )

    [links] => Illuminate\Support\Collection Object
        (
            [items:protected] => Array
                (
                    [0] => Budgetlens\BolRetailerApi\Resources\ProcessStatus\Link Object
                        (
                            [rel] => self
                            [href] => https://api.bol.com/retailer-demo/process-status/1
                            [method] => GET
                        )

                )

            [escapeWhenCastingToString:protected] => 
        )

)
```

### Get Signature (public) keys
```php
$keys = $this->client->subscriptions->getSignatureKeys();
print_r($keys);
```

```php
Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [0] => Budgetlens\BolRetailerApi\Resources\Subscriptions\SignatureKey Object
                (
                    [id] => 0
                    [type] => RSA
                    [publicKey] => MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAh5x6f/IexlWgo23VF9yH7zmRWEaFShAXyFoR3Flh0ZwSn8hZY+rR3zJt/FWKT6mcw51cFjNWGoi92FLLCsXq49TS+8T6z5/AqpS/cORzFndiaPeMN5k9nla9ECwWqizqoBWRFyg7tnP0GkbZF+rjLlAbUznisEItGcfu9rw4+CfulFTwA9CGxKUDNJOcihEkUflsMlGL2Stqs1Q54O2GTPlLX4KTn1z7Iy4E8IqSOm9Z7sEFHK/RR7sf6K3JsN/h3nR0+NN8o2OGCZ1z17OjSnWXbq1QDZIkN+PntEIW540Og0SHDshLAGxOeW0jw8tU/uKZMhR7lmmrabLjutk3NQIDAQAB
                )

        )

    [escapeWhenCastingToString:protected] => 
)
```
---



# Deprecated Endpoints

## Inbounds

### Create Inbound
```diff
- $inbound = new Inbound([
-    'reference' => 'my-reference',
-    'timeSlot' => new Timeslot([
-        'startDateTime' => new \DateTime('2018-04-05 12:00:00'),
-        'endDateTime' => new \DateTime('2018-04-05 17:00:00')
-    ]),
-    'inboundTransporter' => new Transporter([
-        'name' => 'PostNL',
-        'code' => 'PostNL'
-    ]),
-    'labellingService' => false,
-    'products' => [
-        ['ean' => '8718526069331', 'announcedQuantity' => 1],
-        ['ean' => '8718526069332', 'announcedQuantity' => 2],
-        ['ean' => '8718526069333', 'announcedQuantity' => 3],
-        ['ean' => '8718526069334', 'announcedQuantity' => 4]
-    ]
- ]);
- $status = $client->inbounds->create($inbound);
```

### List Inbounds
```diff
- $inbounds = $client->inbounds->list();
```

### Get Inbound Details
```diff
- $inbound = $client->inbounds->get($inboundId);
```

### Get Inbound Packing List
```diff
- $packing = $client->inbounds->getPackingList($inboundId);
- // save pdf  (the filename is $inboundId.pdf)
- $packing->save('$path');
- // save pdf with custom filename
- $packing->save('$path', 'my-filename.pdf');
```
### Get Inbound Shipping Label
```diff
- $label = $client->inbounds->getShippingLabel($inboundId);
- // save pdf  (the filename is $inboundId.pdf)
- $label->save('$path');
- // save pdf with custom filename
- $label->save('$path', 'my-shipping-label.pdf');
```
### Get Product Labels
```diff
- $products = [
-     ['ean' => '0000000000000', 'quantity' => 1],
-     ['ean' => '1111111111111', 'quantity' => 2]
- ];
- $labels = $client->inbounds->getProductLabels($products, LabelFormat::ZEBRA_Z_PERFORM_1000T);
- // save pdf  (the filename is product-labels.pdf)
- $labels->save('$path');
- // save pdf with custom filename
- $labels->save('$path', 'my-product-labels.pdf');
```

### Get Delivery Windows
```diff
- $expectedDeliveryDate = new \DateTime();
- $itemsToSend = 1;
- $deliveryWindows = $client->inbounds->getDeliveryWindows($expectedDeliveryDate, $itemsToSend);
```

### Get Inbound transporters
```diff
- $transporters = $client->inbounds->getTransporters();
```

---




## Usage with Laravel

~~You may incorporate this package in your Laravel application by using [this package](https://github.com/123lens/bol-laravel-client).~~

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing


``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Sebastiaan Blaas][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-bol-retailer-version]: https://img.shields.io/badge/Retailer%20API%20Version-V5-blue
[ico-php-version]: https://img.shields.io/packagist/php-v/budgetlens/bol-retailer-api?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/budgetlens/bol-retailer-api.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-tests]: https://github.com/123lens/bol-retailer-api/actions/workflows/tests.yml/badge.svg
[ico-downloads]: https://img.shields.io/packagist/dt/budgetlens/bol-retailer-api.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/budgetlens/bol-retailer-api
[link-tests]: https://github.com/123lens/bol-retailer-api/actions/workflows/tests.yml?query=workflow%3Atests
[link-downloads]: https://packagist.org/packages/budgetlens/bol-retailer-api
[link-author]: https://github.com/avido
[link-contributors]: https://github.com/123lens/bol-retailer-api/graphs/contributors
