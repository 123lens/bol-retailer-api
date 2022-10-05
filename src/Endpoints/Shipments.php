<?php

namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Shipment;
use Budgetlens\BolRetailerApi\Resources\ShipmentItem;
use Illuminate\Support\Collection;

class Shipments extends BaseEndpoint
{
    /**
     * List All Shipments
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-shipments
     * @param string|null $fulfilmentMethod
     * @param string|null $orderId
     * @param int $page
     * @return Collection
     */
    public function list(string $fulfilmentMethod = null, string $orderId = null, int $page = 1): Collection
    {
        $query = collect([
            'fulfilment-method' => $fulfilmentMethod,
            'order-id' => $orderId,
            'page' => $page
        ])->reject(function ($value) {
            return empty($value);
        })->all();

        $response = $this->performApiCall(
            'GET',
            'shipments' . $this->buildQueryString($query)
        );

        $collection = new Collection();

        $shipments = $response->shipments ?? null;

        if (!is_null($shipments)) {
            collect($shipments)->each(function ($item) use ($collection) {
                $collection->push(new Shipment($item));
            });
        }

        return $collection;
    }

    /**
     * Get Shipment By Id
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-shipment
     * @param string $id
     * @return Shipment
     */
    public function get(string $id): Shipment
    {
        $response = $this->performApiCall(
            'GET',
            "shipments/{$id}"
        );

        return new Shipment(collect($response));
    }
}
