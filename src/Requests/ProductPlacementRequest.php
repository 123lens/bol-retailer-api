<?php

namespace Budgetlens\BolRetailerApi\Requests;

use Budgetlens\BolRetailerApi\Requests\Support\FilterRange;
use Budgetlens\BolRetailerApi\Support\Arr;

class ProductPlacementRequest extends BaseRequest
{
    protected null | string $ean;
}
