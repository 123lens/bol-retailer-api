<?php

namespace Budgetlens\BolRetailerApi\Resources\Retailer;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class RetailerReview extends BaseResource
{
    public null | int $totalReviewCount;
    public null | int $approvalPercentage;
    public null | int $positiveReviewCount;
    public null | int $neutralReviewCount;
    public null | int $negativeReviewCount;

    public function setTotalReviewCountAttribute($value): self
    {
        $this->totalReviewCount = (int) $value;

        return $this;
    }

    public function setApprovalPercentageAttribute($value): self
    {
        $this->approvalPercentage = (int) $value;

        return $this;
    }

    public function setPositiveReviewCountAttribute($value): self
    {
        $this->positiveReviewCount = (int) $value;

        return $this;
    }

    public function setNeutralReviewCountAttribute($value): self
    {
        $this->neutralReviewCount = (int) $value;

        return $this;
    }

    public function setNegativeReviewCountAttribute($value): self
    {
        $this->negativeReviewCount = (int) $value;

        return $this;
    }
}
