<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class PartyName extends BaseResource
{
    public $Name;

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
}
