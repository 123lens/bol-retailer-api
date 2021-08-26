<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Invoice\InvoiceItem;
use Budgetlens\BolRetailerApi\Resources\Invoice\Period;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\AccountingCustomerParty;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\AccountingSupplierParty;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\Currency;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\InvoiceLine;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\InvoiceTypeCode;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\Item;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\LegalMonetaryTotal;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\Price;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\Quantity;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\TaxTotal;
use Illuminate\Support\Collection;

class InvoiceSpecification extends BaseResource
{
    public $id;
    public $invoiceLineRef;
    public $invoicedQuantity;
    public $lineExtensionAmount;
    public $taxTotal;
    public $item;
    public $price;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setInvoicedQuantityAttribute($value): self
    {
        if (!$value instanceof Quantity) {
            $value = new Quantity($value);
        }

        $this->invoicedQuantity = $value;

        return $this;
    }

    public function setLineExtensionAmountAttribute($value): self
    {
        if (!$value instanceof Currency) {
            $value = new Currency($value);
        }

        $this->lineExtensionAmount = $value;

        return $this;
    }

    public function setTaxTotalAttribute($value): self
    {
        if (!$value instanceof TaxTotal) {
            $value = new TaxTotal($value);
        }

        $this->taxTotal = $value;

        return $this;
    }

    public function setItemAttribute($value): self
    {
        if (!$value instanceof Item) {
            $value = new Item($value);
        }

        $this->item = $value;

        return $this;
    }

    public function setPriceAttribute($value): self
    {
        if (!$value instanceof Price) {
            $value = new Price($value);
        }

        $this->price = $value;

        return $this;
    }
}
