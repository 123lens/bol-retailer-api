<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\InvalidFormatException;
use Budgetlens\BolRetailerApi\Resources\Commission;
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

        echo "<pre>";
        print_r($collection);
        exit;

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
            'unit-price' => number_format($unitPrice/100, 2, '.'),
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
