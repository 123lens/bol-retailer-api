<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\InvalidFormatException;
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
use Budgetlens\BolRetailerApi\Resources\Timeslot;
use Budgetlens\BolRetailerApi\Resources\Transporter;
use Budgetlens\BolRetailerApi\Types\InboundState;
use Budgetlens\BolRetailerApi\Types\LabelFormat;
use Illuminate\Support\Collection;

class Inbounds extends BaseEndpoint
{
    private $availableStates = [
        InboundState::ARRIVEDATWH,
        InboundState::CANCELLED,
        InboundState::DRAFT,
        InboundState::PREANNOUNCED
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
     * Get Inbounds
     *
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-inbounds
     * @param string|null $reference
     * @param string|null $bsku
     * @param \DateTime|null $creationStartDate
     * @param \DateTime|null $creationEndDate
     * @param string|null $state
     * @param int $page
     * @return Collection
     */
    public function list(
        string $reference = null,
        string $bsku = null,
        \DateTime $creationStartDate = null,
        \DateTime $creationEndDate = null,
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
            'bsku' => $bsku,
            'creation-start-date' => $creationStartDate,
            'creation-end-date' => $creationEndDate,
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
            "inbounds" . $this->buildQueryString($parameters->all())
        );

        $collection = new Collection();

        collect($response->inbounds)->each(function ($item) use ($collection) {
            $collection->push(new Inbound($item));
        });

        return $collection;
    }

    /**
     * Get Inbound by Id
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-inbound
     * @param string $inboundId
     * @return Inbound
     */
    public function get(string $inboundId): Inbound
    {
        $response = $this->performApiCall(
            'GET',
            "inbounds/{$inboundId}"
        );

        return new Inbound(collect($response));
    }

    /**
     * Create Inbound Shipment
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/post-inbound
     * @param Inbound $inbound
     * @return ProcessStatus
     */
    public function create(Inbound $inbound): ProcessStatus
    {
        $response = $this->performApiCall(
            'POST',
            'inbounds',
            $inbound->toJson()
        );

        return new ProcessStatus(collect($response));
    }

    /**
     * Get Inbound Packing List
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get%20packing%20list
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
                'Accept' => 'application/vnd.retailer.v5+pdf'
            ]
        );
        return new InboundPackinglist([
            'id' => $inboundId,
            'contents' => $response
        ]);
    }

    /**
     * Get Inbound Shipping Label
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-inbound-shipping-label
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
                'Accept' => 'application/vnd.retailer.v5+pdf'
            ]
        );
        return new InboundShippingLabel([
            'id' => $inboundId,
            'contents' => $response
        ]);
    }

    /**
     * Get Product Labels
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-product-labels
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
                'Accept' => 'application/vnd.retailer.v5+pdf'
            ]
        );

        return new InboundProductLabels([
            'id' => 'product-labels',
            'contents' => $response
        ]);
    }

    /**
     * Get Delivery Windows
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-delivery-windows
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
     * @see https://api.bol.com/retailer/public/redoc/v5#operation/get-inbound-transporters
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
