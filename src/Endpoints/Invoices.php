<?php
namespace Budgetlens\BolRetailerApi\Endpoints;

use Budgetlens\BolRetailerApi\Exceptions\InvalidFormatException;
use Budgetlens\BolRetailerApi\Resources\Invoice\InvoicePDF;
use Budgetlens\BolRetailerApi\Resources\Invoice\InvoiceXML;
use Budgetlens\BolRetailerApi\Resources\Invoice as InvoiceResource;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;
use Budgetlens\BolRetailerApi\Resources\InvoiceSpecification;
use Illuminate\Support\Collection;

class Invoices extends BaseEndpoint
{
    private $availableFormats = [
        'xml',
        'pdf',
        'json'
    ];

    /**
     * Get All Invoices
     * @see https://api.bol.com/retailer/public/redoc/v5#tag/Invoices
     * @param \DateTime|null $periodStartDate
     * @param \DateTime|null $periodEndDate
     * @return InvoiceResource
     */
    public function list(\DateTime $periodStartDate = null, \DateTime $periodEndDate = null): InvoiceResource
    {
        $parameters = collect([
            'period-start-date' => $periodStartDate,
            'period-end-date' => $periodEndDate,
        ])->reject(function ($value) {
            return empty($value);
        })->map(function ($value) {
            return $value->format('Y-m-d');
        });

        $response = $this->performApiCall(
            'GET',
            "invoices" . $this->buildQueryString($parameters->all())
        );

        return new InvoiceResource(collect($response));
    }

    public function get(string $invoiceId, $format = 'json')
    {
        if (!in_array($format, $this->availableFormats)) {
            throw new InvalidFormatException();
        }

        $response = $this->performApiCall(
            'GET',
            "invoices/{$invoiceId}",
            null,
            [
                'Accept' => $this->getAcceptHeader($format)
            ]
        );
        switch ($format) {
            case 'xml':
                return new InvoiceXML([
                    'id' => $invoiceId,
                    'contents' => $response
                ]);
            case 'pdf':
                return new InvoicePDF([
                    'id' => $invoiceId,
                    'contents' => $response
                ]);
            default:
                return new InvoiceDetailed(collect($response));
        }
    }

    public function getSpecification(string $invoiceId, $format = 'json'): Collection
    {
        if (!in_array($format, $this->availableFormats)) {
            throw new InvalidFormatException();
        }

        $response = $this->performApiCall(
            'GET',
            "invoices/{$invoiceId}/specification",
            null,
            [
                'Accept' => $this->getAcceptHeader($format)
            ]
        );

        switch ($format) {
//            case 'xml':
//                return new InvoiceXML([
//                    'id' => $invoiceId,
//                    'contents' => $response
//                ]);
//            case 'pdf':
//                return new InvoicePDF([
//                    'id' => $invoiceId,
//                    'contents' => $response
//                ]);
            default:
                $collection = new Collection();

                collect($response->invoiceSpecification)->each(function ($item) use ($collection) {
                    $collection->push(new InvoiceSpecification($item));
                });

                return $collection;
        }
    }

    /**
     * Get Accept Header
     * @param $format
     * @return string
     */
    private function getAcceptHeader($format): string
    {
        switch ($format) {
            case 'pdf':
                return 'application/vnd.retailer.v5+pdf';
            case 'xml':
                return 'application/vnd.retailer.v5+xml';
            default:
                return 'application/vnd.retailer.v5+json';
        }
    }
}
