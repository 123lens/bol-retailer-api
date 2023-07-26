<?php

namespace Budgetlens\BolRetailerApi\Resources\Filters;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class FilterRange extends BaseResource
{
    public $rangeId;
    public $rangeName;
    public $min;
    public $max;
    public $unit;
}
