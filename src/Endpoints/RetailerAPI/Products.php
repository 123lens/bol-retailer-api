<?php

namespace Budgetlens\BolRetailerApi\Endpoints\RetailerAPI;

use Budgetlens\BolRetailerApi\Endpoints\BaseEndpoint;
use Budgetlens\BolRetailerApi\Requests\ListProductsRequest;
use Budgetlens\BolRetailerApi\Resources\FiltersList;
use Budgetlens\BolRetailerApi\Resources\Product\Asset;
use Budgetlens\BolRetailerApi\Resources\ProductList;
use Budgetlens\BolRetailerApi\Resources\Subscription;
use Budgetlens\BolRetailerApi\Support\Str;
use Illuminate\Support\Collection;

class Products extends BaseEndpoint
{

    public function list(ListProductsRequest $request): null | ProductList
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

    public function listFilters(ListProductsRequest $request): null | FiltersList
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

    public function getAssets(string $eancode, null | string $usage = null): Collection
    {
        $parameters = collect([
            'usage' => $usage
        ])
            ->reject(function ($value) {
                return is_null($value);
            })->all();

        $response = $this->performApiCall(
            'GET',
            "products/{$eancode}/assets" . $this->buildQueryString($parameters)
        );

        $collection = new Collection();

        collect($response->assets)->each(function ($item) use ($collection) {
            $collection->push(new Asset($item));
        });

        return $collection;
    }
}
