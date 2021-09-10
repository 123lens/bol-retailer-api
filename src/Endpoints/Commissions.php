<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Commission;
use Illuminate\Support\Collection;

class Commissions extends BaseEndpoint
{
    /**
     * Get Commissions
     *
     * @see https://api.bol.com/retailer/public/redoc/v5#tag/Commissions
     * @param string|null $reference
     * @param string|null $bsku
     * @param \DateTime|null $creationStartDate
     * @param \DateTime|null $creationEndDate
     * @param string|null $state
     * @param int $page
     * @return Collection
     */
    public function list(
        array $commissionQueries = []
    ): Collection {
        if (!count($commissionQueries)) {
            throw new \InvalidArgumentException("Missing commissionQueries");
        }

        $response = $this->performApiCall(
            'POST',
            "commission",
            json_encode([
                'commissionQueries' => $commissionQueries
            ])
        );

        $collection = new Collection();

        collect($response->commissions)->each(function ($item) use ($collection) {
            $collection->push(new Commission($item));
        });

        return $collection;
    }

    /**
     * Get Commission by Eancode
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-commission
     * @param string $eancode
     * @param int $unitPrice price in cents
     * @param string $condition
     * @return Commission
     */
    public function get(string $eancode, int $unitPrice, string $condition = 'NEW'): Commission
    {
        $parameters = collect([
            'condition' => $condition,
            'unit-price' => number_format($unitPrice, 2, '.', ','),
        ])->reject(function ($value) {
            return empty($value);
        });

        $response = $this->performApiCall(
            'GET',
            "commission/{$eancode}" . $this->buildQueryString($parameters->all())
        );

        return new Commission(collect($response));
    }
}
