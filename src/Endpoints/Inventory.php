<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Illuminate\Support\Collection;
use Budgetlens\BolRetailerApi\Resources\Inventory as InventoryResource;

class Inventory extends BaseEndpoint
{

    /**
     * Retrieve Inventory
     * @see https://api.bol.com/retailer/public/redoc/v4#operation/get-inventory
     * @param string|null $quantity
     * @param string|null $stock
     * @param string|null $state
     * @param string|null $query
     * @param int $page
     * @return Collection
     */
    public function get(
        string $quantity = null,
        string $stock = null,
        string $state = null,
        string $query = null,
        int $page = 1
    ): Collection {
        $parameters = collect([
            'quantity' => $quantity,
            'stock' => $stock,
            'state' => $stock,
            'query' => $query,
            'page' => $page
        ])->reject(function ($value) {
            return empty($value);
        });

        $response = $this->performApiCall(
            'GET',
            "inventory" . $this->buildQueryString($parameters->all())
        );

        $collection = new Collection();

        collect($response->inventory)->each(function ($item) use ($collection) {
            $collection->push(new InventoryResource($item));
        });

        return $collection;
    }
}