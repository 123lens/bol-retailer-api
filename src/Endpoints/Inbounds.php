<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\InvalidFormatException;
use Budgetlens\BolRetailerApi\Resources\Inbound;
use Budgetlens\BolRetailerApi\Resources\Invoice\InvoicePDF;
use Budgetlens\BolRetailerApi\Resources\Invoice\InvoiceXML;
use Budgetlens\BolRetailerApi\Resources\Invoice as InvoiceResource;
use Budgetlens\BolRetailerApi\Resources\Order as OrderResource;
use Illuminate\Support\Collection;

class Inbounds extends BaseEndpoint
{
    public function list(
        string $reference = null,
        string $bsku = null,
        \DateTime $creationStartDate = null,
        \DateTime $creationEndDate = null,
        string $state = null,
        int $page = 1
    ): Collection {
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

}
