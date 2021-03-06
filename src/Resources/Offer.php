<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

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
    public $mutationDateTime;
    public $store;
    public $notPublishableReasons;

    public function setMutationDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->mutationDateTime = $value;

        return $this;
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
            if (is_string($value)) {
                $value = ['name' => $value];
            }
            $value = new Condition($value);
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
     * Set Pricing Attribute
     * @param $value
     * @return $this
     */
    public function setPricingAttribute($value): self
    {
        if (!$value instanceof Pricing) {
            $value = new Pricing($value);
        }

        $this->pricing = $value;

        return $this;
    }

    /**
     * Set Offer Price
     * @param string $price
     * @return $this
     */
    public function setPriceAttribute(float $price): self
    {
        $this->pricing = (new Pricing())
            ->add($price);

        return $this;
    }

    /**
     * Set Stock
     * @param $value
     * @return $this
     */
    public function setStockAttribute($value): self
    {
        if (!$value instanceof Stock) {
            if (is_numeric($value)) {
                $value = ['amount' => $value];
            }
            $value = new Stock($value);
        }

        $this->stock = $value;

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
            if (is_string($value)) {
                $value = ['method' => $value];
            }
            $value = new Fulfilment($value);
        }
        $this->fulfilment = $value;

        return $this;
    }

    /**
     * Set Store Attribute
     * @param $value
     * @return $this
     */
    public function setStoreAttribute($value): self
    {
        if (!$value instanceof Store) {
            $value = new Store($value);
        }

        $this->store = $value;

        return $this;
    }

    /**
     * Set Not Published Reasons
     * @param $value
     * @return $this
     */
    public function setNotPublishableReasonsAttribute($value): self
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            $items->push(new NotPublishedReason($item));
        });
        $this->notPublishableReasons = $items;

        return $this;
    }

    /**
     * Output to array
     * @return array
     */
    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->condition), function ($collection) {
                return $collection
                    ->put('condition', $this->condition->toArray());
            })
            ->when(!is_null($this->pricing), function ($collection) {
                return $collection
                    ->put('pricing', $this->pricing->toArray());
            })
            ->when(!is_null($this->stock), function ($collection) {
                return $collection
                    ->put('stock', $this->stock->toArray());
            })
            ->when(!is_null($this->fulfilment), function ($collection) {
                return $collection
                    ->put('fulfilment', $this->fulfilment->toArray());
            })
            ->all();
    }
}
