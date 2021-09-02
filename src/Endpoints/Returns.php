<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Returns as ReturnsResource;
use Budgetlens\BolRetailerApi\Types\ReturnResultTypes;
use Illuminate\Support\Collection;

class Returns extends BaseEndpoint
{
    /**
     * Get Returns
     *
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-returns
     * @param string|null $reference
     * @param string|null $eancode
     * @param \DateTime|null $startDate
     * @param \DateTime|null $endDate
     * @param string|null $state
     * @param int $page
     * @return Collection
     */
    public function list(?string $fulfilmentMethod = null, bool $handled = false, int $page = 1): Collection
    {
        $parameters = collect([
            'fulfilment-method' => $fulfilmentMethod,
            'handled' => (int)$handled,
            'page' => $page
        ])->reject(function ($value) {
            return is_null($value);
        });

        $response = $this->performApiCall(
            'GET',
            "returns" . $this->buildQueryString($parameters->all())
        );

        $collection = new Collection();

        $returns = $response->returns ?? null;

        if (!is_null($returns)) {
            collect($returns)->each(function ($item) use ($collection) {
                $collection->push(new ReturnsResource($item));
            });
        }

        return $collection;
    }

    /**
     * Get Return by Id
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-return
     * @param string $returnId
     * @return ReturnsResource
     */
    public function get(string $returnId): ReturnsResource
    {
        $response = $this->performApiCall(
            'GET',
            "returns/{$returnId}"
        );

        return new ReturnsResource(collect($response));
    }

    /**
     * Create Return
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/create-return
     * @param string $orderItemId
     * @param int $quantity
     * @param string $handlingResult
     * @return ProcessStatus
     */
    public function create(
        string $orderItemId,
        int $quantity = 1,
        string $handlingResult = ReturnResultTypes::RETURN_RECEIVED
    ): ProcessStatus {
        $payload = collect([
            'orderItemId' => $orderItemId,
            'quantityReturned' => $quantity,
            'handlingResult' => $handlingResult
        ]);

        $response = $this->performApiCall(
            'POST',
            'returns',
            $payload->toJson()
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Handle Return
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/handle-return
     * @param string $rmaId
     * @param int $quantity
     * @param string $handlingResult
     * @return ProcessStatus
     */
    public function handle(
        string $rmaId,
        int $quantity = 1,
        string $handlingResult = ReturnResultTypes::RETURN_RECEIVED
    ): ProcessStatus {
        $payload = collect([
            'quantityReturned' => $quantity,
            'handlingResult' => $handlingResult
        ]);

        $response = $this->performApiCall(
            'PUT',
            "returns/{$rmaId}",
            $payload->toJson()
        );

        return new ProcessStatus(collect($response));
    }
}
