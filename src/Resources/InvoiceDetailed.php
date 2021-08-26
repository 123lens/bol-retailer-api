<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Invoice\InvoiceItem;
use Budgetlens\BolRetailerApi\Resources\Invoice\Period;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\AccountingCustomerParty;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\AccountingSupplierParty;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\InvoiceLine;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\InvoiceTypeCode;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\LegalMonetaryTotal;
use Budgetlens\BolRetailerApi\Resources\InvoiceDetailed\TaxTotal;
use Illuminate\Support\Collection;

class InvoiceDetailed extends BaseResource
{
    public $UBLVersionID;
    public $CustomizationID;
    public $ProfileID;
    public $ID;
    public $IssueDate;
    public $InvoiceTypeCode;
    public $DocumentCurrencyCode;
    public $period;
    public $AccountingSupplierParty;
    public $AccountingCustomerParty;
    public $TaxTotal;
    public $LegalMonetaryTotal;
    public $InvoiceLine;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setUBLVersionIDAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->UBLVersionID = $value;

        return $this;
    }

    public function setCustomizationIDAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->CustomizationID = $value;

        return $this;
    }

    public function setProfileIDAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->ProfileID = $value;

        return $this;
    }

    public function setIDAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->ID = $value;

        return $this;
    }

    /**
     * Set Issue Date Attribute
     * @param $value
     * @return $this
     */
    public function setIssueDateAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->issueDate = new \DateTime();
        $this->issueDate->setTimestamp($value);

        return $this;
    }

    public function setInvoiceTypeCodeAttribute($value): self
    {
        if (!$value instanceof InvoiceTypeCode) {
            $value = new InvoiceTypeCode($value);
        }

        $this->InvoiceTypeCode = $value;

        return $this;
    }

    public function setDocumentCurrencyCodeAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->DocumentCurrencyCode = $value;

        return $this;
    }

    public function setAccountingSupplierPartyAttribute($value): self
    {
        if (!$value instanceof AccountingSupplierParty) {
            $value = new AccountingSupplierParty($value);
        }

        $this->AccountingSupplierParty = $value;

        return $this;
    }

    public function setAccountingCustomerPartyAttribute($value): self
    {
        if (!$value instanceof AccountingCustomerParty) {
            $value = new AccountingCustomerParty($value);
        }

        $this->AccountingCustomerParty = $value;

        return $this;
    }

    public function setTaxTotalAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof TaxTotal) {
                $item = new TaxTotal($item);
            }
            $items->push($item);
        });

        $this->TaxTotal = $items;

        return $this;
    }

    public function setLegalMonetaryTotalAttribute($value): self
    {
        if (!$value instanceof LegalMonetaryTotal) {
            $value = new LegalMonetaryTotal($value);
        }

        $this->LegalMonetaryTotal = $value;

        return $this;
    }

    public function setInvoiceLineAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof InvoiceLine) {
                $item = new InvoiceLine($item);
            }
            $items->push($item);
        });
        $this->InvoiceLine = $items;

        return $this;
    }
}
