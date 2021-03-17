<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\Shipment as ShipmentResource;
use Illuminate\Support\Collection;
use Budgetlens\BolRetailerApi\Resources\Order as OrderResource;

class Orders extends BaseEndpoint
{
    protected function getApiVersionHeader(): string
    {
        return 'application/vnd.retailer.v5+json';
    }

    public function getOpenOrders(string $fulfillmentMethod = 'FBR', int $page = 1)
    {
        $this->setApiVersionHeader($this->getApiVersionHeader());

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


    public function get(int $id): OrderResource
    {
        $this->setApiVersionHeader($this->getApiVersionHeader());

        $response = $this->performApiCall(
            'GET',
            "orders/{$id}"
        );

        return new OrderResource(collect($response));
    }

    protected function getShipmentsResource(string $apiMethod, string $message = ''): ShipmentResource
    {
        $response = $this->performApiCall(
            'GET',
            $apiMethod
        );

        $shipment = collect($response->data->shipments)->first();

        if ($shipment === null) {
            throw new BolRetailerException($message);
        }

        return new ShipmentResource(
            collect($shipment)->all()
        );
    }
}
