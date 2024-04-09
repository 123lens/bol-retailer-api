<?php

namespace Budgetlens\BolRetailerApi\Resources\Filters;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class FilterRange extends BaseResource
{
    public ?string $rangeId;
    public ?string $rangeName;
    public $min;
    public $max;
    public ?string $unit;
}
