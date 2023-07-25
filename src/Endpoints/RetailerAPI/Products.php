<?php

namespace Budgetlens\BolRetailerApi\Endpoints\RetailerAPI;

use Budgetlens\BolRetailerApi\Endpoints\BaseEndpoint;
use Budgetlens\BolRetailerApi\Requests\ListProductsRequest;
use Budgetlens\BolRetailerApi\Resources\ProductList;
use Budgetlens\BolRetailerApi\Resources\Retailer;

class Products extends BaseEndpoint
{

    public function list(ListProductsRequest $request)
    {
        $response = $this->performApiCall(
            'POST',
            "products/list",
            $request->toJson()
        );

        $result = $response ?? null;

        if (!is_null($result)) {
            return new ProductList($result);
        }

        return null;
    }

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
