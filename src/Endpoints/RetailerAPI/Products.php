<?php

namespace Budgetlens\BolRetailerApi\Endpoints\RetailerAPI;

use Budgetlens\BolRetailerApi\Endpoints\BaseEndpoint;
use Budgetlens\BolRetailerApi\Requests\ListProductsRequest;
use Budgetlens\BolRetailerApi\Resources\FiltersList;
use Budgetlens\BolRetailerApi\Resources\ProductList;
use Budgetlens\BolRetailerApi\Resources\Retailer;
use Budgetlens\BolRetailerApi\Support\Str;

class Products extends BaseEndpoint
{

    public function list(ListProductsRequest $request)
    {
        $response = $this->performApiCall(
            'POST',
            "products/list",
            $request->toJson(),
            $request->getHeaders()
        );

        $result = $response ?? null;

        if (!is_null($result)) {
            return new ProductList($result);
        }

        return null;
    }

    public function listFilters(ListProductsRequest $request)
    {
        $parameters = collect($request->toArray())
            ->map(function ($data, $key) {
                return [Str::snake($key, '-') => $data];
            })
            ->collapse()
            ->reject(function ($value) {
                return is_null($value);
            })->all();

        $response = $this->performApiCall(
            'GET',
            "products/list-filters" . $this->buildQueryString($parameters)
        );

        $result = $response ?? null;

        if (!is_null($result)) {
            return new FiltersList($result);
        }

        return null;
    }

    public function getAssets(string $eancode, null | string $usage = null)
    {
        $parameters = collect([
            'usage' => $usage
        ])
            ->reject(function ($value) {
                return is_null($value);
            })->all();

        $response = $this->performApiCall(
            'GET',
            "products/{$eancode}" . $this->buildQueryString($parameters)
        );
        return new Retailer(collect($response));
    }
}
