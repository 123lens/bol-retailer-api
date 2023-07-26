<?php
namespace Budgetlens\BolRetailerApi\Resources\Product;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Product\Assets\Variant;
use Budgetlens\BolRetailerApi\Resources\Reduction;
use Illuminate\Support\Collection;

class CompetingOffer extends BaseResource
{
    public null | string $offerId;
    public null | string $retailerId;
    public null | string $countryCode;
    public null | bool $bestOffer;
    public null | float $price;
    public null | string $fulfilmentMethod;
    public null | string $condition;
    public null | string $ultimateOrderTime;
    public null | \DateTimeImmutable $minDeliveryDate;
    public null | \DateTimeImmutable $maxDeliveryDate;

    public function setMinDeliveryDateAttribute($value): self
    {
        $this->minDeliveryDate = new \DateTimeImmutable($value);

        return $this;
    }

    public function setMaxDeliveryDateAttribute($value): self
    {
        $this->maxDeliveryDate = new \DateTimeImmutable($value);

        return $this;
    }
}
