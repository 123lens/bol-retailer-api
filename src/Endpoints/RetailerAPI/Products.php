<?php

namespace Budgetlens\BolRetailerApi\Endpoints\RetailerAPI;

use Budgetlens\BolRetailerApi\Endpoints\BaseEndpoint;
use Budgetlens\BolRetailerApi\Requests\ListProductsRequest;
use Budgetlens\BolRetailerApi\Requests\ProductPlacementRequest;
use Budgetlens\BolRetailerApi\Resources\FiltersList;
use Budgetlens\BolRetailerApi\Resources\Product\Asset;
use Budgetlens\BolRetailerApi\Resources\Product\CompetingOffer;
use Budgetlens\BolRetailerApi\Resources\ProductIds;
use Budgetlens\BolRetailerApi\Resources\ProductList;
use Budgetlens\BolRetailerApi\Resources\ProductPlacement;
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

    public function getCompetingOffers(
        string $eancode,
        string $countryCode = "NL",
        bool $bestOfferOnly = false,
        string $condition = 'ALL',
        int $page = 1,
    ): Collection {
        $parameters = collect([
            'country-code' => $countryCode,
            'best-offer-only' => $bestOfferOnly,
            'condition' => $condition,
            'page' => $page
        ])
            ->reject(function ($value) {
                return is_null($value);
            })->all();

        $response = $this->performApiCall(
            'GET',
            "products/{$eancode}/offers" . $this->buildQueryString($parameters)
        );

        $collection = new Collection();

        collect($response->offers)->each(function ($item) use ($collection) {
            $collection->push(new CompetingOffer($item));
        });

        return $collection;
    }

    public function getPlacement(ProductPlacementRequest $request): null | ProductPlacement
    {
        $response = $this->performApiCall(
            'GET',
            "products/{$request->ean}/placement" . $this->buildQueryString($request->getQuery()),
            null,
            $request->getHeaders()
        );

        $result = $response ?? null;

        if (!is_null($result)) {
            return new ProductPlacement($result);
        }

        return null;
    }

    public function getProductIds(string $eancode): null | ProductIds
    {
        $response = $this->performApiCall(
            'GET',
            "products/{$eancode}/product-ids",
        );
        $result = $response ?? null;

        if (!is_null($result)) {
            return new ProductIds($result);
        }

        return null;
    }
}
