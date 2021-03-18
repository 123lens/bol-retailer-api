<?php
namespace Budgetlens\BolRetailerApi\Resources;

class LabelPrice extends BaseResource
{
    public $totalPrice;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Set Total Price
     * @param $value
     * @return $this
     */
    public function setTotalPriceAttribute($value): self
    {
        $this->totalPrice = (int)($value * 100);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'totalPrice' => number_format(($this->totalPrice/100), 2)
        ];
    }
}
