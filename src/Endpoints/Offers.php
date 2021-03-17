<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Resources\Condition;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Shipment as ShipmentResource;
use Illuminate\Support\Collection;
use Budgetlens\BolRetailerApi\Resources\Order as OrderResource;

class Offers extends BaseEndpoint
{

    /**
     * Retrieve offer by Offer ID
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/offers.html#_get_offer
     * @param string $offerId
     * @return Offer
     */
    public function get(string $offerId): Offer
    {
        $response = $this->performApiCall(
            'GET',
            "offers/{$offerId}"
        );

        return new Offer(collect($response));
    }

    /**
     * Create new offer
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/offers.html#_create_new_offer
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
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/offers.html#_update_offer
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
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/offers.html#_delete_offer
     * @param string $offerId
     * @return ProcessStatus
     */
    public function delete(string $offerId): ProcessStatus
    {
        $response = $this->performApiCall(
            'DELETE',
            "offers/{$offerId}"
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Request Offers Export
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/offers.html#_offers_export_api_endpoints
     * @return ProcessStatus
     */
    public function requestExport(): ProcessStatus
    {
        $response = $this->performApiCall(
            'POST',
            'offers/export',
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
        $this->setApiVersionHeader('application/vnd.retailer.v4+csv');

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
}
