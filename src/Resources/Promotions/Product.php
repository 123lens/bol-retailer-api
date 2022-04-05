<?php
namespace Budgetlens\BolRetailerApi\Resources\Promotions;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Product extends BaseResource
{
    public $ean;
    public $relevanceScores;
    public $maximumPrice;

    public function setRelevanceScoresAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof RelevanceScore) {
                $item = new RelevanceScore($item);
            }

            $items->push($item);
        });

        $this->relevanceScores = $items;

        return $this;
    }
}
