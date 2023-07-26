<?php

namespace Budgetlens\BolRetailerApi\Resources\Filters;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Filter extends BaseResource
{
    public null | string $filterName;
    public null | array $filterValues;

    public function setFilterValuesAttribute($value): self
    {
        foreach ($value as $filter) {
            $this->filterValues[] = new FilterValue($filter);
        }

        return $this;
    }
}

