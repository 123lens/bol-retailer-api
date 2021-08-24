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

        collect($response->returns)->each(function ($item) use ($collection) {
            $collection->push(new ReturnsResource($item));
        });

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
     * Create Replenishment
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/post-replenishment
     * @param Replenishment $inbound
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
     * Retrieve available pickup timeslots
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/post-pickup-time-slots
     * @param Address $address
     * @param int $numberOfLoadCarriers
     * @return Collection
     */
    public function pickupTimeslots(Address $address, int $numberOfLoadCarriers)
    {
        $payload = collect([
            'address' => $address->toArray(),
            'numberOfLoadCarriers' => $numberOfLoadCarriers
        ])->toJson();

        $response = $this->performApiCall(
            'POST',
            'replenishments/pickup-time-slots',
            $payload
        );

        $collection = new Collection();

        collect($response->timeSlots)->each(function ($item) use ($collection) {
            $collection->push(new Replenishment\PickupTimeslot($item));
        });

        return $collection;
    }

    /**
     * Get Product Labels
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/post-product-labels
     * @param array $products
     * @param string $format
     * @return ProductLabels
     */
    public function productLabels(
        array  $products,
        string $format = LabelFormat::ZEBRA_Z_PERFORM_1000T
    ): ProductLabels {
        if (!in_array($format, $this->availableProductLabelFormat)) {
            throw new \InvalidArgumentException("Invalid format");
        }

        $payload = collect([
            'products' => $products,
            'labelFormat' => $format
        ])->reject(function ($item) {
            return is_array($item) && !count($item);
        })->toJson();

        $response = $this->performApiCall(
            'POST',
            "replenishments/product-labels",
            $payload,
            [
                'Content-Type' => 'application/vnd.retailer.v5+json',
                'Accept' => 'application/vnd.retailer.v5+pdf'
            ]
        );

        return new ProductLabels([
            'id' => 'product-labels',
            'contents' => $response
        ]);
    }

    /**
     * Update Replenishment
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/put-replenishment
     * @param Replenishment $inbound
     * @return ProcessStatus
     */
    public function update(Replenishment $replenishment): ProcessStatus
    {
        $payload = collect($replenishment->toArray())
            ->forget('replenishmentId')
            ->toJson();

        $response = $this->performApiCall(
            'PUT',
            "replenishments/{$replenishment->replenishmentId}",
            $payload
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Update Replenishment
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/put-replenishment
     * @param string $replenishmentId
     * @param string $labelType
     * @return CarrierLabels
     */
    public function loadCarrierLabels(string $replenishmentId, string $labelType = 'WAREHOUSE'): CarrierLabels
    {
        $response = $this->performApiCall(
            'GET',
            "replenishments/{$replenishmentId}/load-carrier-labels" . $this->buildQueryString([
                'label-type' => $labelType
            ]),
            null,
            [
                'Accept' => 'application/vnd.retailer.v5+pdf'
            ]
        );

        return new CarrierLabels([
            'id' => $replenishmentId,
            'contents' => $response
        ]);
    }

    /**
     * Get Picklist
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-pick-list
     * @param string $replenishmentId
     * @return Picklist
     */
    public function picklist(string $replenishmentId): Picklist
    {
        $response = $this->performApiCall(
            'GET',
            "replenishments/{$replenishmentId}/pick-list",
            null,
            [
                'Accept' => 'application/vnd.retailer.v5+pdf'
            ]
        );

        return new Picklist([
            'id' => $replenishmentId,
            'contents' => $response
        ]);
    }
}
