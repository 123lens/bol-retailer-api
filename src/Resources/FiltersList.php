<?php

namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Filters\Category;
use Budgetlens\BolRetailerApi\Resources\Filters\Filter;
use Budgetlens\BolRetailerApi\Resources\Filters\FilterRange;
use Illuminate\Support\Collection;

class FiltersList extends BaseResource
{
    public $categoryData;
    public $filterRanges;
    public $filters;

    public function setCategoryDataAttribute($value): self
    {
        $this->categoryData = new Category($value);

        return $this;
    }

    public function setFilterRangesAttribute($value): self
    {
        foreach ($value as $item) {
            $this->filterRanges[] = new FilterRange($item);
        }

        return $this;
    }

    public function setFiltersAttribute($value): self
    {
        foreach ($value as $filter) {
            $this->filters[] = new Filter($filter);
        }

        return $this;
    }
}
