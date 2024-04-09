<?php

namespace Budgetlens\BolRetailerApi\Resources\Retailer;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class RetailerRating extends BaseResource
{
    public ?float $retailerRating;
    public ?float $productInformationRating;
    public ?float $deliveryTimeRating;
    public ?float $shippingRating;
    public ?float $serviceRating;

    public function setRetailerRatingAttribute($value): self
    {
        $this->retailerRating = (float) $value;

        return $this;
    }

    public function setProductInformationRatingAttribute($value): self
    {
        $this->productInformationRating = (float) $value;

        return $this;
    }

    public function setDeliveryTimeRatingAttribute($value): self
    {
        $this->deliveryTimeRating = (float) $value;

        return $this;
    }

    public function setShippingRatingAttribute($value): self
    {
        $this->shippingRating = (float) $value;

        return $this;
    }

    public function setServiceRatingAttribute($value): self
    {
        $this->serviceRating = (float) $value;

        return $this;
    }
}
