<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class TaxSubTotal extends BaseResource
{
    public $TaxableAmount;
    public $TaxAmount;
    public $TaxCategory;


    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setTaxableAmountAttribute($value): self
    {
        if (!$value instanceof Currency) {
            $value = new Currency($value);
        }

        $this->TaxableAmount = $value;

        return $this;
    }

    public function setTaxAmountAttribute($value): self
    {
        if (!$value instanceof Currency) {
            $value = new Currency($value);
        }

        $this->TaxAmount = $value;

        return $this;
    }

    public function setTelephoneAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->Telephone = $value;

        return $this;
    }

    public function setTaxCategoryAttribute($value): self
    {
        if (!$value instanceof TaxCategory) {
            $value = new TaxCategory($value);
        }

        $this->TaxCategory = $value;

        return $this;
    }
}
