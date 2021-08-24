<?php
namespace Budgetlens\BolRetailerApi\Resources\Shipment;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Product;

class ShipmentItem extends BaseResource
{
    public $orderItemId;
    public $ean;
    public $fulfilment;
    public $offer;
    public $product;
    public $quantity;
    public $unitPrice;
    public $commission;

    /**
     * Set Fulfilment Attribute
     * @param $value
     * @return $this
     */
    public function setFulfilmentAttribute($value): self
    {
        if (!$value instanceof Fulfilment) {
            $value = new Fulfilment($value);
        }

        $this->fulfilment = $value;

        return $this;
    }

    /**
     * Set Offer Attribute
     * @param $value
     * @return $this
     */
    public function setOfferAttribute($value): self
    {
        if (!$value instanceof Offer) {
            $value = new Offer($value);
        }

        $this->offer = $value;

        return $this;
    }

    /**
     * Set Product Attribute
     * @param $value
     * @return $this
     */
    public function setProductAttribute($value): self
    {
        if (!$value instanceof Product) {
            $value = new Product($value);
        }

        $this->product = $value;

        return $this;
    }
}
