<?php
namespace Budgetlens\BolRetailerApi\Tests\Feature\Endpoints;

use Budgetlens\BolRetailerApi\Resources\Invoice;
use Budgetlens\BolRetailerApi\Resources\Invoice\InvoiceItem;
use Budgetlens\BolRetailerApi\Tests\TestCase;
use Illuminate\Support\Collection;

class InvoicesTest extends TestCase
{
    /** @test */
    public function getAllInvoices()
    {
        $this->useMock('200-get-all-invoices.json');

        $invoices = $this->client->invoices->list();

        $this->assertInstanceOf(Invoice::class, $invoices);
        $this->assertInstanceOf(Collection::class, $invoices->invoiceListItems);
        $this->assertInstanceOf(Invoice\Period::class, $invoices->period);
        $this->assertCount(1, $invoices->invoiceListItems);
        $this->assertInstanceOf(InvoiceItem::class, $invoices->invoiceListItems->first());
        $this->assertSame('4500022543921', $invoices->invoiceListItems->first()->invoiceId);
        $this->assertIsArray($invoices->invoiceListItems->first()->invoiceMediaTypes);
        $this->assertIsArray($invoices->invoiceListItems->first()->specificationMediaTypes);
    }

    /** @test */
    public function getInvoiceById()
    {
        $id = '4500022543921';
        $invoice = $this->client->invoices->get($id);
        print_r($invoice);
        exit;
    }
    /** @test */
    public function getInvoicePdf()
    {
        $id = '4500022543921';
        $invoice = $this->client->invoices->get($id, 'pdf');
        $this->assertInstanceOf(Invoice\InvoicePDF::class, $invoice);
        $this->assertSame('pdf', $invoice->type);
        $this->assertSame($id, $invoice->id);
    }

    /** @test */
    public function getInvoiceXml()
    {
        $this->useMock(
            '200-get-invoice.xml',
            200,
            [
                'Content-Type' => [
                    'application/vnd.retailer.v4+xml;charset=UTF-8'
                ]
            ]
        );
        $id = '4500022543921';

        $invoice = $this->client->invoices->get($id, 'xml');
        $this->assertInstanceOf(Invoice\InvoiceXML::class, $invoice);
        $this->assertSame('xml', $invoice->type);
        $this->assertSame($id, $invoice->id);
    }
}
