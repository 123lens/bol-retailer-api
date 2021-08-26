<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class LegalMonetaryTotal extends BaseResource
{
    public $LineExtensionAmount;
    public $TaxExclusiveAmount;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setLineExtensionAmountAttribute($value): self
    {
        if (!$value instanceof Currency) {
            $value = new Currency($value);
        }

        $this->LineExtensionAmount = $value;

        return $this;
    }

    public function setTaxExclusiveAmountAttribute($value): self
    {
        if (!$value instanceof Currency) {
            $value = new Currency($value);
        }

        $this->TaxExclusiveAmount = $value;

        return $this;
    }
}
