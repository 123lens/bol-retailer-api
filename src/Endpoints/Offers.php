<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Resources\Condition;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Shipment as ShipmentResource;
use Budgetlens\BolRetailerApi\Support\Arr;
use Illuminate\Support\Collection;
use Budgetlens\BolRetailerApi\Resources\Order as OrderResource;

class Offers extends BaseEndpoint
{

    /**
     * Retrieve offer by Offer ID
     * @see https://api.bol.com/retailer/public/Retailer-API/v5/functional/offers.html#_get_offer
     * @param string|Offer $offerId
     * @return Offer
     */
    public function get($offerId): Offer
    {
        if ($offerId instanceof Offer) {
            $offerId = $offerId->offerId;
        }

        $response = $this->performApiCall(
            'GET',
            "offers/{$offerId}"
        );

        return new Offer(collect($response));
    }

    /**
     * Create new offer
     * @see https://api.bol.com/retailer/public/Retailer-API/v5/functional/offers.html#_create_new_offer
     * @param Offer $offer
     * @return ProcessStatus
     */
    public function create(Offer $offer): ProcessStatus
    {
        $response = $this->performApiCall(
            'POST',
            'offers',
            $offer->toJson()
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Update Offer
     * @see https://api.bol.com/retailer/public/Retailer-API/v5/functional/offers.html#_update_offer
     * @param Offer $offer
     * @return ProcessStatus
     */
    public function update(Offer $offer): ProcessStatus
    {
        $update = collect([
            'reference' => $offer->reference,
            'onHoldByRetailer' => $offer->onHoldByRetailer,
            'unknownProductTitle' => $offer->unknownProductTitle,
            'fulfilment' => $offer->fulfilment->toArray()
        ])->reject(function ($item) {
            return empty($item);
        })->all();

        $response = $this->performApiCall(
            'PUT',
            "offers/{$offer->offerId}",
            json_encode($update)
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Delete Offer
     * @see https://api.bol.com/retailer/public/Retailer-API/v5/functional/offers.html#_delete_offer
     * @param string|Offer $offerId
     * @return ProcessStatus
     */
    public function delete($offerId): ProcessStatus
    {
        if ($offerId instanceof Offer) {
            $offerId = $offerId->offerId;
        }

        $response = $this->performApiCall(
            'DELETE',
            "offers/{$offerId}"
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Update Offer Price
     * @see https://api.bol.com/retailer/public/Retailer-API/v5/functional/offers.html#_update_offer_price
     * @param Offer $offer
     * @return ProcessStatus
     */
    public function updatePrice(Offer $offer): ProcessStatus
    {
        $prices = $offer->pricing->bundlePrices->map(function ($item) {
            return [
                'quantity' => $item->quantity,
                'unitPrice' => number_format($item->unitPrice/100, 2)
            ];
        })->all();

        $body = json_encode([
            'pricing' => [
                'bundlePrices' => $prices
            ]
        ]);

        $response = $this->performApiCall(
            'PUT',
            "offers/{$offer->offerId}/price",
            $body
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Update Offer Stock
     * @see https://api.bol.com/retailer/public/Retailer-API/v5/functional/offers.html#_update_offer_stock
     * @param Offer $offer
     * @return ProcessStatus
     */
    public function updateStock(Offer $offer): ProcessStatus
    {
        $response = $this->performApiCall(
            'PUT',
            "offers/{$offer->offerId}/stock",
            json_encode([
                'amount' => $offer->stock->amount,
                'managedByRetailer' => $offer->stock->managedByRetailer
            ])
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Request Offers Export
     * @see https://api.bol.com/retailer/public/Retailer-API/v5/functional/offers.html#_offers_export_api_endpoints
     * @param string $format
     * @return ProcessStatus
     */
    public function requestExport(string $format = 'CSV'): ProcessStatus
    {
        $response = $this->performApiCall(
            'POST',
            'offers/export',
            json_encode([
                'format' => $format
            ])
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Request Unpublished Offers Export
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/post-unpublished-offer-report
     * @param string $format
     * @return ProcessStatus
     */
    public function requestUnpublishedExport(string $format = 'CSV'): ProcessStatus
    {
        $response = $this->performApiCall(
            'POST',
            'offers/unpublished',
            json_encode([
                'format' => 'CSV'
            ])
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Retrieve CSV Export
     * Columns/headers:
     * offerId,ean,conditionName,conditionCategory,conditionComment,bundlePricesPrice,fulfilmentDeliveryCode,
     * stockAmount,onHoldByRetailer,fulfilmentType,mutationDateTime,referenceCode
     * @param string $id
     * @return Collection
     */
    public function getExport(string $id): Collection
    {
        $this->setApiVersionHeader('application/vnd.retailer.v5+csv');

        $response = $this->performApiCall(
            'GET',
            "offers/export/{$id}"
        );
        $lines = collect(explode("\n", $response))->reject(function ($line) {
            return empty($line);
        });
        // shift first line
        $lines->shift();

        $collection = new Collection();

        $lines->each(function ($item) use ($collection) {
            $cols = explode(",", $item);
            $collection->push(new Offer([
                'offerId' => $cols[0],
                'ean' => $cols[1],
                'condition' => new Condition([
                    'name' => $cols[2],
                    'category' => $cols[3],
                    'comment' => $cols[4]
                ]),
                'price' => $cols[5] * 100,
                'fulfilment' => new Fulfilment([
                    'method' => $cols[9],
                    'deliveryCode' => $cols[6],
                ]),
                'stock' => $cols[7],
                'onHoldByRetailer' => $cols[8],
                'mutationDateTime' => $cols[10],
                'reference' => $cols[11]
            ]));
        });

        return $collection;
    }

    /**
     * Retrieve Upublished Offers CSV Export
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/post-unpublished-offer-report
     * Columns/headers:
     * offerId,ean,notPublishableReason,notPublishableReasonDescription
     * @param string $id
     * @return Collection
     */
    public function getUnpublishedExport(string $id): Collection
    {
        $this->setApiVersionHeader('application/vnd.retailer.v5+csv');

        $response = $this->performApiCall(
            'GET',
            "offers/unpublished/{$id}"
        );

        $lines = collect(explode("\n", $response))->reject(function ($line) {
            return empty($line);
        })->map(function ($item) {
            return explode(",", $item);
        });
        // shift first line
        $headers = $lines->shift();

        // apply headers to columns
        $items = collect(Arr::replaceKeyWithNames($lines->all(), $headers));

        $collection = new Collection();

        $items->each(function ($item) use ($collection) {
            $collection->push(new Offer([
                'offerId' => $item['offerId'] ?? null,
                'ean' => $item['ean'] ?? null,
                'notPublishableReasons' => [
                    [
                        'code' => $item['notPublishableReason'] ?? null,
                        'description' => $item['notPublishableReasonDescription'] ?? null
                    ]
                ]
            ]));
        });

        return $collection;
    }
}
