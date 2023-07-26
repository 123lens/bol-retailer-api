<?php

namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Product\ProductRating;
use Illuminate\Support\Collection;

class ProductRatings extends BaseResource
{
    public null | Collection $ratings;

    public function getTotalVotes(): int
    {
        return $this->ratings->sum(function ($item) {
            return $item->count;
        });
    }

    public function getAverage(): float
    {
        $sum = $this->ratings->sum(function ($item) {
            return $item->count * $item->rating;
        });

        if ($sum > 0) {
            return number_format($sum / $this->getTotalVotes(), 2);
        }

        return 0;
    }

    public function setRatingsAttribute($value): self
    {
        $items = [];

        foreach ($value as $item) {
            $items[] = new ProductRating($item);
        }

        $this->ratings = collect($items);

        return $this;
    }
}
