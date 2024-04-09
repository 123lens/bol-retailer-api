<?php

namespace Budgetlens\BolRetailerApi\Resources\Filters;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Filter extends BaseResource
{
    public ?string $filterName;
    public ?Collection $filterValues;

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

