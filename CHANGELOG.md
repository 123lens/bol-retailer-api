# Changelog

All notable changes to `bol-retailer-api` will be documented in this file.

## [Unreleased]

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
