<?php

namespace Budgetlens\BolRetailerApi\Requests\Support;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class FilterRange extends BaseResource
{
    protected ?string $rangeId;
    protected ?int $min;
    protected ?int $max;
}
