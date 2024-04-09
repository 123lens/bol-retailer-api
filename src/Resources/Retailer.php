<?php

namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Retailer\RetailerRating;
use Budgetlens\BolRetailerApi\Resources\Retailer\RetailerReview;

class Retailer extends BaseResource
{
    public ?string $retailerId;
    public ?string $displayName;
    public ?\DateTimeImmutable $registrationDate;
    public ?bool $topRetailer;
    public ?string $ratingMethod;
    public ?RetailerRating $retailerRating;

    public ?RetailerReview $retailerReview;

    public function setRegistrationDateAttribute($value): self
    {
        $this->registrationDate = new \DateTimeImmutable($value);

        return $this;
    }

    public function setTopRetailerAttribute($value): self
    {
        $this->topRetailer = (bool) $value;

        return $this;
    }

    public function setRetailerRatingAttribute($value): self
    {
        $this->retailerRating = new RetailerRating($value);

        return $this;
    }

    public function setRetailerReviewAttribute($value): self
    {
        $this->retailerReview = new RetailerReview($value);

        return $this;
    }
}
