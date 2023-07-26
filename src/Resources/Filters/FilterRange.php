<?php

namespace Budgetlens\BolRetailerApi\Resources\Filters;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class FilterRange extends BaseResource
{
    public null | string $rangeId;
    public null | string $rangeName;
    public null | int | float $min;
    public null | int | float $max;
    public null | string $unit;
}
