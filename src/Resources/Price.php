<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Price extends BaseResource
{
    public $quantity;
    public $unitPrice;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Set Unit Price
     * @param $value
     * @return $this
     */
    public function setUnitPriceAttribute($value): self
    {
        $this->unitPrice = (int)($value * 100);

        return $this;
    }
}
