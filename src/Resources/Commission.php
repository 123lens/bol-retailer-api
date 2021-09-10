<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class Commission extends BaseResource
{
    public $ean;
    public $condition;
    public $unitPrice;
    public $fixedAmount;
    public $percentage;
    public $totalCost;
    public $totalCostWithoutReduction;
    public $reductions;

    public function __construct($attributes = [])
    {
        $this->condition = 'NEW';

        parent::__construct($attributes);
    }

    public function setReductionsAttribute($value)
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Reduction) {
                $item = new Reduction($item);
            }
            $items->push($item);
        });

        $this->reductions = $items;

        return $this;
    }
}
