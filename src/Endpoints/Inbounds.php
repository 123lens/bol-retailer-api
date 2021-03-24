<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\InvalidFormatException;
use Budgetlens\BolRetailerApi\Resources\Inbound;
use Budgetlens\BolRetailerApi\Resources\Invoice\InvoicePDF;
use Budgetlens\BolRetailerApi\Resources\Invoice\InvoiceXML;
use Budgetlens\BolRetailerApi\Resources\Invoice as InvoiceResource;
use Budgetlens\BolRetailerApi\Resources\Order as OrderResource;
use Budgetlens\BolRetailerApi\Resources\Timeslot;
use Budgetlens\BolRetailerApi\Resources\Transporter;
use Budgetlens\BolRetailerApi\Types\InboundState;
use Illuminate\Support\Collection;

class Inbounds extends BaseEndpoint
{
    private $availableStates = [
        InboundState::ARRIVEDATWH,
        InboundState::CANCELLED,
        InboundState::DRAFT,
        InboundState::PREANNOUNCED
    ];

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

    public function get(string $inboundId): Inbound
    {
        $response = $this->performApiCall(
            'GET',
            "inbounds/{$inboundId}"
        );

        return new Inbound(collect($response));
    }

    /**
     * Get Delivery Windows
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
