<?php

namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Filters\Category;
use Budgetlens\BolRetailerApi\Resources\Filters\Filter;
use Budgetlens\BolRetailerApi\Resources\Filters\FilterRange;
use Illuminate\Support\Collection;

class FiltersList extends BaseResource
{
    public null | Category $categoryData;
    public null | Collection $filterRanges;
    public null | Collection $filters;

    public function setCategoryDataAttribute($value): self
    {
        $this->categoryData = new Category($value);

        return $this;
    }

    public function setFilterRangesAttribute($value): self
    {
        $items = [];
        foreach ($value as $item) {
            $items[] = new FilterRange($item);
        }

        $this->filterRanges = collect($items);

        return $this;
    }

    public function setFiltersAttribute($value): self
    {
        $items = [];

        foreach ($value as $filter) {
            $items[] = new Filter($filter);
        }

        $this->filters = collect($items);

        return $this;
    }
}
