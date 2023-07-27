# Changelog

All notable changes to `bol-retailer-api` will be documented in this file.

## [Unreleased]

## [v9.0.2] - 2023-07-27

### Changes
- Products: Check for correct return set for competing product offers
- Products: Check for correct return set for product assets

## [v9.0.1] - 2023-07-26

### Changes
Restored illuminate/collections requirements for versions: ^8.32 || ^v9.34 

## [v9.0.0] - 2023-07-26

### Added
- Added Get Retailer By ID
- Added Products -> Get Product List
- Added Products -> Get Product List Filters
- Added Products -> Get Product Assets
- Added Products -> Get a list of competing offers by EAN
- Added Products -> Get Product Placement
- Added Products -> Get Product Ids By Eancode
- Added Products -> Get Product Ratings By Eancode

### Changes
- Drop php7 support
- Updated composer package dependencies

## [v8.2.0] - 2023-03-30

### Changes
- `DeliveryOption` => Cast `validUntilDate` to `DateTime()`

## [v8.1.4] - 2023-03-30

### Changes
- Hotfix, checking if fulfilment var is present

## [v8.1.3] - 2023-03-30

### Changes
- Refactored getDeliveryInfo, to ignore fulfilment FBB

## [v8.1.2] - 2023-03-29

### Changes
- Refactored getDeliveryInfo, to ignore other fields in orderItems except `orderItemId`

## [v8.1.1] - 2023-03-29

### Changes
- New release with correct requirement for illuminate/collections

## [v8.1.0] - 2023-03-29

### Added 
- Added `OrderHash` to Order resource

## [v8.0.2] - 2022-10-11

### Changes
- Format order-item \DateTime

## [v8.0.1] - 2022-10-11

### Changes
- Updated illuminate/collections requirements

## [v8.0.0] - 2022-03-30

### Changes
- Replenishments
  - Added Product Destinations
  - Added Destination Warehouses
  
## [v6.0.0] - 2022-03-30

### Changes
- Processed changed resources https://api.bol.com/retailer/public/Retailer-API/v6/migrationguide/v5-v6/migrationguide.html
- Changed main version header to v6
- Update ProcessStatus resource, set `createTimestamp` as `DateTime`
- Added endpoint `subscriptions`
- Added method `getDeliveryDates` to `/replenishment` endpoint

## [v5.0.2] - 2021-09-21

### Changes
- Commissions endpoint
- Insights Endpoint
- Replenishment Endpoint
- Returns endpoint
- added transports endpoint

## [v2.2.0] - 2020-11-11

### Added
- ...

### Removed
- ...

## [v0.1.1] - 2018-08-26

### Added


## [v0.1.0] - 2018-08-24

### Initial release


[v9.0.2]: https://github.com/123lens/bol-retailer-api/compare/v9.0.1...v9.0.2
[v9.0.1]: https://github.com/123lens/bol-retailer-api/compare/v9.0.0...v9.0.1
[v9.0.0]: https://github.com/123lens/bol-retailer-api/compare/v8.2.0...v9.0.0
[v8.2.0]: https://github.com/123lens/bol-retailer-api/compare/v8.1.4...v8.2.0
[v8.1.4]: https://github.com/123lens/bol-retailer-api/compare/v8.1.3...v8.1.4
[v8.1.3]: https://github.com/123lens/bol-retailer-api/compare/v8.1.2...v8.1.3
[v8.1.2]: https://github.com/123lens/bol-retailer-api/compare/v8.1.1...v8.1.2
[v8.1.1]: https://github.com/123lens/bol-retailer-api/compare/v8.0.1...v8.1.1
[v8.1.0]: https://github.com/123lens/bol-retailer-api/compare/v8.0.2...v8.1.0
[v8.0.2]: https://github.com/123lens/bol-retailer-api/compare/v8.0.1...v8.0.2
[v8.0.1]: https://github.com/123lens/bol-retailer-api/compare/v8.0.0...v8.0.1
[v8.0.0]: https://github.com/123lens/bol-retailer-api/compare/v6.0.4...v8.0.0
[v2.2.0]: https://github.com/123lens/bol-retailer-api/compare/v2.1.0...v2.2.0
[v0.1.0]: https://github.com/123lens/bol-retailer-api/tree/v0.1.0
