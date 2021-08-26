<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class AccountingSupplierParty extends BaseResource
{
    public $Party;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setPartyAttribute($value): self
    {
        if (!$value instanceof Party) {
            $value = new Party($value);
        }

        $this->Party = $value;

        return $this;
    }
}
