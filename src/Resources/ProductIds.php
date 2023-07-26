<?php

namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class ProductIds extends BaseResource
{
    public null | string $bolProductId;
    public null | Collection $eans;

    public function setEansAttribute($value): self
    {
        $this->eans = collect($value)->map(function ($item) {
            return $item->ean;
        });

        return $this;
    }
}
