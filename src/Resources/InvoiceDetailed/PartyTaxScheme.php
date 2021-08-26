<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class PartyTaxScheme extends BaseResource
{
    public $CompanyID;
    public $TaxScheme;


    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setCompanyIDAttribute($value): self
    {
        if (!$value instanceof Scheme) {
            $value = new Scheme($value);
        }

        $this->CompanyID = $value;

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
