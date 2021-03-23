<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Exceptions\ValidationException;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Shipment as ShipmentResource;
use Budgetlens\BolRetailerApi\Resources\Transport;
use Budgetlens\BolRetailerApi\Types\CancelReasonCodes;
use Illuminate\Support\Collection;
use Budgetlens\BolRetailerApi\Resources\Order as OrderResource;

class Orders extends BaseEndpoint
{
    /**
     * Get Open Orders
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/orders-shipments.html#_retrieve_open_orders
     * @param string $fulfillmentMethod
     * @param int $page
     * @return Collection
     */
    public function getOpenOrders(string $fulfillmentMethod = 'FBR', int $page = 1)
    {
        $response = $this->performApiCall(
            'GET',
            'orders' . $this->buildQueryString([
                'fulfilment-method' => $fulfillmentMethod,
                'page' => $page
            ])
        );
        $collection = new Collection();

        collect($response->orders)->each(function ($item) use ($collection) {
            $collection->push(new OrderResource($item));
        });

        return $collection;
    }

    /**
     * Retrieve a single order
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/orders-shipments.html#_retrieve_a_single_order
     * @param int $id
     * @return OrderResource
     */
    public function get(int $id): OrderResource
    {
        $response = $this->performApiCall(
            'GET',
            "orders/{$id}"
        );

        return new OrderResource(collect($response));
    }

    /**
     * Ship Order Item
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/orders-shipments.html#_ship_order_item
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
     * Cancel Order Item
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/orders-shipments.html#_cancel_order_item
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
}
