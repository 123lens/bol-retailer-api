<?php

namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Transport;
use Budgetlens\BolRetailerApi\Types\CancelReasonCodes;
use Illuminate\Support\Collection;
use Budgetlens\BolRetailerApi\Resources\Order as OrderResource;

class Orders extends BaseEndpoint
{
    /**
     * Get Orders
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-orders
     * @param string $fulfillmentMethod
     * @param string $status (ALL, SHIPPED or OPEN)
     * @param int $changeIntervalMinute - To filter on the period in minutes during which the latest
     *                                      change was performed on an order item. (<= 2800)
     * @param \DateTime $latestChangeDate -  To filter on the date on which the latest change was performed on an
     *                                      order item. Up to 3 months of history is supported.
     * @param int $page
     * @return Collection
     */
    public function getOrders(
        string $fulfillmentMethod = 'FBR',
        string $status = 'ALL',
        int $changeIntervalMinute = 0,
        ?\DateTime $latestChangeDate = null,
        int $page = 1
    ): Collection {
        $query = collect([
            'fulfilment-method' => $fulfillmentMethod,
            'status' => $status,
            'change-interval-minute' => $changeIntervalMinute,
            'latest-change-date' => !is_null($latestChangeDate) ? $latestChangeDate->format('Y-m-d') : null,
            'page' => $page
        ])->reject(function ($value) {
            return empty($value) || (int) $value === 0;
        })->all();

        $response = $this->performApiCall(
            'GET',
            'orders' . $this->buildQueryString($query)
        );

        $collection = new Collection();

        $orders = $response->orders ?? null;

        if (!is_null($orders)) {
            collect($orders)->each(function ($item) use ($collection) {
                $collection->push(new OrderResource($item));
            });
        }

        return $collection;
    }

    /**
     * Get Open Orders
     * @param string $fulfillmentMethod
     * @param int $page
     * @return Collection
     */
    public function getOpenOrders(string $fulfillmentMethod = 'FBR', int $page = 1)
    {
        return $this->getOrders($fulfillmentMethod, 'OPEN', $page);
    }

    /**
     * Cancel Order Item
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/cancel-order-item
     * @param string $orderItemId
     * @param string $reasonCode
     * @return ProcessStatus
     */
    public function cancelOrderItem(
        string $orderItemId,
        string $reasonCode = CancelReasonCodes::DEFAULT
    ): ProcessStatus {
        $payload = collect([
            'orderItems' => [
                'orderItemId' => $orderItemId,
                'reasonCode' => $reasonCode
            ]
        ]);

        $response = $this->performApiCall(
            'PUT',
            'orders/cancellation',
            json_encode($payload->all())
        );

        return new ProcessStatus(collect($response));
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
            'PUT',
            'orders/shipment',
            json_encode($payload->all())
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Retrieve a single order
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-order
     * @param string $id
     * @return OrderResource
     */
    public function get(string $id): OrderResource
    {
        $response = $this->performApiCall(
            'GET',
            "orders/{$id}"
        );

        return new OrderResource(collect($response));
    }
}
