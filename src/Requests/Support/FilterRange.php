<?php

namespace Budgetlens\BolRetailerApi\Requests\Support;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class FilterRange extends BaseResource
{
    protected null | string $rangeId;
    protected null | int $min;
    protected null | int $max;
}
