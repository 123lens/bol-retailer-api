<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\BolRetailerException;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Order;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Shipment as ShipmentResource;
use Illuminate\Support\Collection;
use Budgetlens\BolRetailerApi\Resources\Order as OrderResource;

class Offers extends BaseEndpoint
{
    public function create(Offer $offer)
    {
        $response = $this->performApiCall(
            'POST',
            'offers',
            $offer->toJson()
        );

        return new ProcessStatus(collect($response));
    }
}
