<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\InvalidFormatException;
use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\Inbound;
use Budgetlens\BolRetailerApi\Resources\InboundPackinglist;
use Budgetlens\BolRetailerApi\Resources\InboundProductLabels;
use Budgetlens\BolRetailerApi\Resources\InboundShippingLabel;
use Budgetlens\BolRetailerApi\Resources\Invoice\InvoicePDF;
use Budgetlens\BolRetailerApi\Resources\Invoice\InvoiceXML;
use Budgetlens\BolRetailerApi\Resources\Invoice as InvoiceResource;
use Budgetlens\BolRetailerApi\Resources\Label;
use Budgetlens\BolRetailerApi\Resources\Order as OrderResource;
use Budgetlens\BolRetailerApi\Resources\ProcessStatus;
use Budgetlens\BolRetailerApi\Resources\Replenishment;
use Budgetlens\BolRetailerApi\Resources\Timeslot;
use Budgetlens\BolRetailerApi\Resources\Transporter;
use Budgetlens\BolRetailerApi\Types\InboundState;
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
        string $reference = null,
        string $eancode = null,
        \DateTime $startDate = null,
        \DateTime $endDate = null,
        string $state = null,
        int $page = 1
    ): Collection {
        if (!is_null($state) &&
            !in_array($state, $this->availableStates)
        ) {
            throw new \InvalidArgumentException("Invalid state");
        }

        $parameters = collect([
            'reference' => $reference,
            'eanc' => $eancode,
            'start-date' => $startDate,
            'end-date' => $endDate,
            'state' => $state,
            'page' => $page
        ])->reject(function ($value) {
            return empty($value);
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

        collect($response->replenishments)->each(function ($item) use ($collection) {
            $collection->push(new Replenishment($item));
        });

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
     * Get Inbound Packing List
     * @see https://api.bol.com/retailer/public/redoc/v4#operation/get%20packing%20list
     * @param string $inboundId
     * @return InboundPackinglist
     */
    public function getPackingList(string $inboundId): InboundPackinglist
    {
        $response = $this->performApiCall(
            'GET',
            "inbounds/{$inboundId}/packinglist",
            null,
            [
                'Accept' => 'application/vnd.retailer.v4+pdf'
            ]
        );
        return new InboundPackinglist([
            'id' => $inboundId,
            'contents' => $response
        ]);
    }

    /**
     * Get Inbound Shipping Label
     * @see https://api.bol.com/retailer/public/redoc/v4#operation/get-inbound-shipping-label
     * @param string $inboundId
     * @return InboundShippingLabel
     */
    public function getShippingLabel(string $inboundId): InboundShippingLabel
    {
        $response = $this->performApiCall(
            'GET',
            "inbounds/{$inboundId}/shippinglabel",
            null,
            [
                'Accept' => 'application/vnd.retailer.v4+pdf'
            ]
        );
        return new InboundShippingLabel([
            'id' => $inboundId,
            'contents' => $response
        ]);
    }

    /**
     * Get Product Labels
     * @see https://api.bol.com/retailer/public/redoc/v4#operation/get-product-labels
     * @param array $productLabels
     * @param string $format
     * @return InboundProductLabels
     */
    public function getProductLabels(
        array $productLabels,
        string $format = LabelFormat::ZEBRA_Z_PERFORM_1000T
    ): InboundProductLabels {
        if (!in_array($format, $this->availableProductLabelFormat)) {
            throw new \InvalidArgumentException("Invalid format");
        }

        $payload = collect([
            'productLabels' => $productLabels,
            'format' => $format
        ])->reject(function ($item) {
            return is_array($item) && !count($item);
        })->all();
        $response = $this->performApiCall(
            'POST',
            "inbounds/productlabels",
            json_encode($payload),
            [
                'Content-Type' => 'application/vnd.retailer.v4+json',
                'Accept' => 'application/vnd.retailer.v4+pdf'
            ]
        );

        return new InboundProductLabels([
            'id' => 'product-labels',
            'contents' => $response
        ]);
    }

    /**
     * Get Delivery Windows
     * @see https://api.bol.com/retailer/public/redoc/v4#operation/get-delivery-windows
     * @param \DateTime|null $deliveryDate
     * @param int $itemsToSend
     * @return Collection
     */
    public function getDeliveryWindows(\DateTime $deliveryDate = null, int $itemsToSend = 1): Collection
    {
        $parameters = collect([
            'delivery-date' => $deliveryDate,
            'items-to-send' => $itemsToSend
        ])->reject(function ($value) {
            return empty($value);
        })->map(function ($value) {
            return ($value instanceof \DateTime)
                ? $value->format('Y-m-d')
                : $value;
        });

        $response = $this->performApiCall(
            'GET',
            "inbounds/delivery-windows" . $this->buildQueryString($parameters->all())
        );

        $collection = new Collection();

        collect($response->timeSlots)->each(function ($item) use ($collection) {
            $collection->push(new Timeslot($item));
        });

        return $collection;
    }

    /**
     * Get Transporters
     * @see https://api.bol.com/retailer/public/redoc/v4#operation/get-inbound-transporters
     * @return Collection
     */
    public function getTransporters(): Collection
    {
        $response = $this->performApiCall(
            'GET',
            "inbounds/inbound-transporters"
        );

        $collection = new Collection();

        collect($response->transporters)->each(function ($item) use ($collection) {
            $collection->push(new Transporter($item));
        });

        return $collection;
    }
}
