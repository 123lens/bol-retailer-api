<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class PartyLegalEntity extends BaseResource
{
    public $CompanyID;


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
}
