<?php

namespace Budgetlens\BolRetailerApi\Endpoints\RetailerAPI;

use Budgetlens\BolRetailerApi\Endpoints\BaseEndpoint;
use Budgetlens\BolRetailerApi\Resources\Retailer;
use Illuminate\Support\Collection;

class Retailers extends BaseEndpoint
{
    public function get(
        string $retailerId,
    ): Collection {
        $response = $this->performApiCall(
            'GET',
            "retailers/{$retailerId}"
        );

        return new Retailer(collect($response));
    }
}
