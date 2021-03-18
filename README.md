# Bol.com Retailer API client

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
$bol = new \Budgetlens\BolRetailerApi\Client();
```
Or Use your own config.
``` php
$bol = new \Budgetlens\BolRetailerApi\Client(new CustomApiConfig());
```

# Examples

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
``` php
$order = $client->orders->get($orderId);
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

[ico-php-version]: https://img.shields.io/packagist/php-v/budgetlens/bol-retailer-api?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/budgetlens/bol-retailer-api.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-tests]: https://img.shields.io/github/workflow/status/budgetlens/bol-retailer-api/tests/main?label=tests&style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bol/bol-retailer-api.svg?style=flat-square
[ico-code-style]: https://styleci.io/repos/xxxx/shield?branch=main

[link-packagist]: https://packagist.org/packages/budgetlens/bol-retailer-api
[link-tests]: https://github.com/123lens/bol-retailer-api/actions?query=workflow%3Atests
[link-downloads]: https://packagist.org/packages/budgetlens/bol-retailer-api
[link-code-style]: https://styleci.io/repos/xxxx
