<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\DeliveryOption;
use Budgetlens\BolRetailerApi\Resources\Order;
use Illuminate\Support\Collection;

class Shipping extends BaseEndpoint
{
    public function createLabel(Order $order)
    {

    }

    /**
     * Retrieve delivery Options
     * @see https://api.bol.com/retailer/public/Retailer-API/v4/functional/shipping-labels.html
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

        collect($response->deliveryOptions)->each(function ($item) use ($collection) {
            $collection->push(new DeliveryOption($item));
        });

        return $collection;
    }

    public function getLabel(string $id)
    {

    }
}
