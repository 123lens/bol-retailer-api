<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class TaxCategory extends BaseResource
{
    public $ID;
    public $Percent;
    public $TaxScheme;


    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setIDAttribute($value): self
    {
        if (!$value instanceof Scheme) {
            $value = new Scheme($value);
        }

        $this->ID = $value;

        return $this;
    }

    public function setPercentAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->Percent = $value;

        return $this;
    }

    public function setTaxSchemeAttribute($value): self
    {
        if (is_object($value) && property_exists($value, 'ID')) {
            $value = $value->ID;
        }

        if (!$value instanceof Scheme) {
            $value = new Scheme($value);
        }

        $this->TaxScheme = $value;

        return $this;
    }
}
