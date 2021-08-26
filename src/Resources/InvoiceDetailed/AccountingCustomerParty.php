<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class AccountingCustomerParty extends BaseResource
{
    public $SupplierAssignedAccountID;
    public $Party;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setSupplierAssignedAccountIDAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->SupplierAssignedAccountID = $value;

        return $this;
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
