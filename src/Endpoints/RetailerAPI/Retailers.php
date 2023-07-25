<?php

namespace Budgetlens\BolRetailerApi\Endpoints\RetailerAPI;

use Budgetlens\BolRetailerApi\Endpoints\BaseEndpoint;
use Budgetlens\BolRetailerApi\Resources\Retailer;

class Retailers extends BaseEndpoint
{
    public function get(
        string | int $retailerId,
    ): Retailer {
        $response = $this->performApiCall(
            'GET',
            "retailers/{$retailerId}"
        );
        return new Retailer(collect($response));
    }
}
