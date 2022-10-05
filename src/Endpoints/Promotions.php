<?php

namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Promotion;
use Budgetlens\BolRetailerApi\Resources\Promotions\Product;
use Illuminate\Support\Collection;

class Promotions extends BaseEndpoint
{
    /**
     * Get Promotions
     *
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-promotions
     * @param array $promotionTypes
     * @param int $page
     * @return Collection
     */
    public function list(array $promotionTypes, int $page = 1): Collection
    {
        $parameters = collect([
            'promotion-type' => $promotionTypes,
            'page' => $page,
        ])->reject(function ($value) {
            return empty($value);
        });

        $response = $this->performApiCall(
            'GET',
            "promotions" . $this->buildQueryString($parameters->all())
        );

        $collection = new Collection();

        collect($response->promotions)->each(function ($item) use ($collection) {
            $collection->push(new Promotion($item));
        });

        return $collection;
    }

    /**
     * Get Promotion By ID
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-promotion
     * @param string $id
     * @return Promotion
     */
    public function get(string $id): Promotion
    {
        $response = $this->performApiCall(
            'GET',
            "promotions/{$id}"
        );

        return new Promotion(collect($response));
    }

    /**
     * Gets a paginated list of all products that are present within a promotion.
     * @see https://api.bol.com/retailer/public/redoc/v8/retailer.html#operation/get-products
     * @param string $id
     * @param int $page
     * @return Collection
     */
    public function products(string $id, int $page = 1)
    {
        $parameters = collect([
            'page' => $page,
        ])->reject(function ($value) {
            return empty($value);
        });

        $response = $this->performApiCall(
            'GET',
            "promotions/{$id}/products" . $this->buildQueryString($parameters->all())
        );

        $collection = new Collection();

        collect($response->products)->each(function ($item) use ($collection) {
            $collection->push(new Product($item));
        });

        return $collection;
    }
}
