<?php

namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Shipment;
use Budgetlens\BolRetailerApi\Resources\ShipmentItem;
use Budgetlens\BolRetailerApi\Resources\Transport;
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

    /**
     * Ship Order Item
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/ship-order-item
     * @param string $orderItemId
     * @param string|null $shipmentReference
     * @param string|null $shipmentLabelId
     * @param Transport|null $transport
     * @return ProcessStatus
     */
    public function shipOrderItem(
        string $orderItemId,
        string $shipmentReference = null,
        string $shipmentLabelId = null,
        ?Transport $transport = null
    ): ProcessStatus {
        $payload = collect([
            'orderItems' => [
                'orderItemId' => $orderItemId
            ],
            'shipmentReference' => $shipmentReference,
            'shippingLabelId' => $shipmentLabelId,
            'transport' => $transport
        ])
            ->when(!is_null($transport), function ($collection) use ($transport) {
                return $collection->put('transport', $transport->toArray());
            })
            ->reject(function ($value) {
                return is_null($value);
            });

        $response = $this->performApiCall(
            'POST',
            'shipments',
            json_encode($payload->all())
        );

        return new ProcessStatus(collect($response));
    }
}
