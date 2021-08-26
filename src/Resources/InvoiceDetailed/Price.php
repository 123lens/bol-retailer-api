<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Price extends BaseResource
{
    public $PriceAmount;
    public $BaseQuantity;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setPriceAmountAttribute($value): self
    {
        if (!$value instanceof Currency) {
            $value = new Currency($value);
        }

        $this->PriceAmount = $value;

        return $this;
    }

    public function setBaseQuantityAttribute($value): self
    {
        if (!$value instanceof Quantity) {
            $value = new Quantity($value);
        }

        $this->BaseQuantity = $value;

        return $this;
    }
}
