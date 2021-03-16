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

Initialize the Api client and set your API key:

``` php
$bol = new \Budgetlens\BolRetailerApi\Client();

$bol->setApiKey('your-api-key');
```

## Examples

### create offer..

``` php
$offer = new \Budgetlens\Avido\Resources\Offer([
    'reference' => 'your own reference for the offer',
    ...
]);
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
