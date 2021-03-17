<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Offer extends BaseResource
{
    public $ean;
    public $offerId;
    public $reference;
    public $condition;
    public $onHoldByRetailer;
    public $unknownProductTitle;
    public $pricing;
    public $stock;
    public $fulfilment;

    public function __construct($attributes = [])
    {
        $this->condition = new Condition();
        $this->pricing = new Pricing();
        $this->stock = new Stock();
        $this->fulfilment = new Fulfilment();

        parent::__construct($attributes);
    }

    /**
     * Set Condition
     *
     * @param $value
     * @return $this
     */
    public function setConditionAttribute($value): self
    {
        if (!$value instanceof Condition) {
            $value = new Condition([
                'name' => $value
            ]);
        }

        $this->condition = $value;

        return $this;
    }

    /**
     * Set On Hold By Retailer
     * @param $value
     * @return $this
     */
    public function setOnHoldByRetailer($value): self
    {
        if (!is_bool($value)) {
            $value = (bool)$value;
        }

        $this->onHoldByRetailer = $value;

        return $this;
    }

    /**
     * Set Offer Price
     * @param int $price
     * @return $this
     */
    public function setPriceAttribute(int $price): self
    {
        $this->pricing = (new Pricing())
            ->add($price);

        return $this;
    }

    /**
     * Set Stock
     * @param $stock
     * @return $this
     */
    public function setStockAttribute($stock): self
    {
        if (!$stock instanceof Stock) {
            $stock = new Stock([
                'amount' => $stock
            ]);
        }

        $this->stock = $stock;

        return $this;
    }

    /**
     * Set Fulfilment
     * @param $value
     * @return $this
     */
    public function setFulfilmentAttribute($value): self
    {
        if (!$value instanceof Fulfilment) {
            $value = new Fulfilment([
                'method' => $value
            ]);
        }
        $this->fulfilment = $value;

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->merge([
                'condition' => $this->condition->toArray(),
                'pricing' => $this->pricing->toArray(),
                'stock' => $this->stock->toArray(),
                'fulfilment' => $this->fulfilment->toArray()
            ])
            ->all();
    }
}
