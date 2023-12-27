<?php

namespace Budgetlens\BolRetailerApi\Resources\Filters;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Filter extends BaseResource
{
    public null | string $filterName;
    public null | Collection $filterValues;

    public function setFilterValuesAttribute($value): self
    {
        $items = [];
        foreach ($value as $filter) {
            $items[] = new FilterValue($filter);
        }

        $this->filterValues = collect($items);

        return $this;
    }
}
