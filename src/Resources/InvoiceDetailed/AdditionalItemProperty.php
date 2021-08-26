<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class AdditionalItemProperty extends BaseResource
{
    public $Name;
    public $Value;

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

    public function setValueAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->Value = $value;

        return $this;
    }
}
