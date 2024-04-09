<?php
namespace Budgetlens\BolRetailerApi\Resources\Product;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Product\Assets\Variant;
use Budgetlens\BolRetailerApi\Resources\Reduction;
use Illuminate\Support\Collection;

class CompetingOffer extends BaseResource
{
    public ?string $offerId;
    public ?string $retailerId;
    public ?string $countryCode;
    public ?bool $bestOffer;
    public ?float $price;
    public ?string $fulfilmentMethod;
    public ?string $condition;
    public ?string $ultimateOrderTime;
    public ?\DateTimeImmutable $minDeliveryDate;
    public ?\DateTimeImmutable $maxDeliveryDate;

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
