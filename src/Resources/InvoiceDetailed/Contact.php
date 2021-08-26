<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Contact extends BaseResource
{
    public $Name;
    public $Telephone;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setNameAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->Name = $value;

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
}
