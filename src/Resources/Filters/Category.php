<?php

namespace Budgetlens\BolRetailerApi\Resources\Filters;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Category extends BaseResource
{
    public ?string $categoryName;
    public ?Collection $categoryValues;

    public function setCategoryValuesAttribute($value): self
    {
        $items = [];
        foreach ($value as $item) {
            $items[] = new CategoryValue($item);
        }

        $this->categoryValues = collect($items);

        return $this;
    }
}
