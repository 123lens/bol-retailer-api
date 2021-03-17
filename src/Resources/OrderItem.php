<?php
namespace Budgetlens\BolRetailerApi\Resources;

class OrderItem extends BaseResource
{
    public $orderItemId;
    public $cancellationRequest;
    public $fulfilment;
    public $offer;
    public $product;
    public $ean;
    public $quantity;
    public $unitPrice;
    public $commission;

    public function setFulfilmentAttribute($value)
    {
        if (!$value instanceof Fulfilment) {
            $value = new Fulfilment($value);
        }

        $this->fulfilment = $value;

        return $this;
    }

    public function setOfferAttribute($value)
    {
        if (!$value instanceof Offer) {
            $value = new Offer($value);
        }

        $this->offer = $value;

        return $this;
    }

    public function setProductAttribute($value)
    {
        if (!$value instanceof Product) {
            $value = new Product($value);
        }

        $this->product = $value;

        return $this;
    }

    public function setUnitPriceAttribute($value)
    {
        // reformat to cents.
        $value = (int)($value * 100);

        $this->unitPrice = $value;

        return $this;
    }

    public function setCommissionAttribute($value)
    {
        $value = (int)($value * 100);

        $this->commission = $value;

        return $this;
    }
}
