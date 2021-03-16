<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Resources\Shipment as ShipmentResource;


class Shipments extends BaseEndpoint
{
    public function list()
    {

    }


    public function get(int $id): ShipmentResource
    {
        return $this->getShipmentsResource(
            "/shipments/{$id}",
            "Shipment with id '{$id}' not found."
        );
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
