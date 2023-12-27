<?php

namespace Budgetlens\BolRetailerApi\Requests;

use Budgetlens\BolRetailerApi\Requests\Support\FilterRange;
use Budgetlens\BolRetailerApi\Support\Arr;

class ListProductsRequest extends BaseRequest
{
    protected null | string $searchTerm;
    protected null | string $categoryId;
    protected null | string $sort;
    protected array $filterRanges = [];
    protected array $filterValues = [];
    protected string $countryCode = "NL";
    protected int $page = 1;


    public function setFilterRangesAttribute($value)
    {
        if (is_array($value) && !Arr::isAssoc($value)) {
            foreach ($value as $filter) {
                $this->addFilterRangesAttribute($filter);
            }
        }
    }

    public function addFilterRangesAttribute($value): self
    {
        $this->filterRanges[] = new FilterRange($value);

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(count($this->filterRanges), function ($collection) {
                $filters = collect($this->filterRanges)->map(function ($item) {
                    return $item->toArray();
                });

                return $collection->put('filterRanges', $filters->all());
            })->all();
    }
}
