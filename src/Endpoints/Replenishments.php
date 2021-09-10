<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Replenishment;
use Budgetlens\BolRetailerApi\Resources\Replenishment\ProductLabels;
use Budgetlens\BolRetailerApi\Resources\Replenishment\Picklist;
use Budgetlens\BolRetailerApi\Resources\Replenishment\CarrierLabels;
use Budgetlens\BolRetailerApi\Types\LabelFormat;
use Budgetlens\BolRetailerApi\Types\ReplenishState;
use Illuminate\Support\Collection;

class Replenishments extends BaseEndpoint
{
    private $availableStates = [
        ReplenishState::ANNOUNCED,
        ReplenishState::IN_TRANSIT,
        ReplenishState::ARRIVED_AT_WH,
        ReplenishState::IN_PROGRESS_AT_WH,
        ReplenishState::CANCELLED,
        ReplenishState::DONE
    ];

    private $availableProductLabelFormat = [
        LabelFormat::AVERY_3474,
        LabelFormat::AVERY_J8159,
        LabelFormat::AVERY_J8160,
        LabelFormat::BROTHER_DK11208D,
        LabelFormat::DYMO_99012,
        LabelFormat::ZEBRA_Z_PERFORM_1000T
    ];

    /**
     * Get Replenishments
     *
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-replenishments
     * @param string|null $reference
     * @param string|null $eancode
     * @param \DateTime|null $startDate
     * @param \DateTime|null $endDate
     * @param string|null $state
     * @param int $page
     * @return Collection
     */
    public function list(
        string    $reference = null,
        string    $eancode = null,
        \DateTime $startDate = null,
        \DateTime $endDate = null,
        array     $states = [],
        int       $page = 1
    ): Collection {
        foreach ($states as $state) {
            if (!in_array($state, $this->availableStates)) {
                throw new \InvalidArgumentException("Invalid state");
            }
        }

        $parameters = collect([
            'reference' => $reference,
            'ean' => $eancode,
            'start-date' => $startDate,
            'end-date' => $endDate,
            'state' => $states,
            'page' => $page
        ])->reject(function ($value) {
            return (is_countable($value) && !count($value) || is_null($value));
        })->map(function ($value) {
            return ($value instanceof \DateTime)
                ? $value->format('Y-m-d')
                : $value;
        });

        $response = $this->performApiCall(
            'GET',
            "replenishments" . $this->buildQueryString($parameters->all())
        );

        $collection = new Collection();

        $replenishments = $response->replenishments ?? null;

        if (!is_null($replenishments)) {
            collect($replenishments)->each(function ($item) use ($collection) {
                $collection->push(new Replenishment($item));
            });
        }

        return $collection;
    }

    /**
     * Get Replenishment by Id
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-replenishment
     * @param string $replenishmentId
     * @return Replenishment
     */
    public function get(string $replenishmentId): Replenishment
    {
        $response = $this->performApiCall(
            'GET',
            "replenishments/{$replenishmentId}"
        );

        return new Replenishment(collect($response));
    }

    /**
     * Create Replenishment
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/post-replenishment
     * @param Replenishment $inbound
     * @return ProcessStatus
     */
    public function create(Replenishment $replenishment): ProcessStatus
    {
        $response = $this->performApiCall(
            'POST',
            'replenishments',
            $replenishment->toJson()
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

        $timeslots = $response->timeSlots ?? null;

        if (!is_null($timeslots)) {
            collect($timeslots)->each(function ($item) use ($collection) {
                $collection->push(new Replenishment\PickupTimeslot($item));
            });
        }

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
