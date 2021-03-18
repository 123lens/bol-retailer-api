<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class Pricing extends BaseResource
{
    public $bundlePrices = [];

    public function setBundlePricesAttribute($value)
    {
        if (is_array($value)) {
            $items = new Collection();
            collect($value)->each(function ($item) use ($items) {
                $items->push(new Price($item));
            });
            $this->bundlePrices = $items;
        }

        return $this;
    }

    /**
     * Add Bundle Price
     * @param $price
     * @param int $quantity
     * @return $this
     */
    public function add($price, int $quantity = 1): self
    {
        $this->bundlePrices[] = new Price([
            'quantity' => $quantity,
            'unitPrice' => $price
        ]);

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(count($this->bundlePrices), function ($collection) {
                $pricing = collect($this->bundlePrices)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('bundlePrices', $pricing->all());
            })
            ->all();
    }
}
