<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\DeliveryOption;
use Budgetlens\BolRetailerApi\Resources\Label;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Illuminate\Support\Collection;

class Shipping extends BaseEndpoint
{
    /**
     * Create Shipping Label
     *
     * @see https://api.bol.com/retailer/public/redoc/v6#operation/post-shipping-label
     * @param Order $order
     * @param string $shippingLabelOfferId
     * @return ProcessStatus
     */
    public function createLabel(Order $order, string $shippingLabelOfferId): ProcessStatus
    {
        $response = $this->performApiCall(
            'POST',
            'shipping-labels',
            json_encode([
                'orderItems' => $order->orderItems->toArray(),
                'shippingLabelOfferId' => $shippingLabelOfferId
            ])
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Retrieve delivery Options
     * @see https://api.bol.com/retailer/public/Retailer-API/v6/functional/shipping-labels.html
     * @param Order $order
     * @return Collection
     */
    public function getDeliveryOptions(Order $order): Collection
    {
        $response = $this->performApiCall(
            'POST',
            'shipping-labels/delivery-options',
            json_encode([
                'orderItems' => $order->orderItems->toArray()
            ])
        );

        $collection = new Collection();

        $deliveryOptions = $response->deliveryOptions ?? null;

        if (!is_null($deliveryOptions)) {
            collect($deliveryOptions)->each(function ($item) use ($collection) {
                $collection->push(new DeliveryOption($item));
            });
        }

        return $collection;
    }

    /**
     * Retrieve label
     *
     * @param string $id
     * @return Label
     */
    public function getLabel(string $id): Label
    {
        $response = $this->performApiCall(
            'GET',
            "shipping-labels/{$id}",
            null,
            [
                'Accept' => 'application/vnd.retailer.v6+pdf'
            ]
        );
        return new Label([
            'id' => $id,
            'label' => $response
        ]);
    }
}
