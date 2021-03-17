<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Pricing extends BaseResource
{
    public $bundlePrices = [];

    /**
     * Add Bundle Price
     * @param $price
     * @param int $quantity
     * @return $this
     */
    public function add($price, int $quantity = 1): self
    {
        if (!is_int($price)) {
            $price = $price * 100;
        }
        $this->bundlePrices[] = [
            'quantity' => $quantity,
            'unitPrice' => $price
        ];

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(count($this->bundlePrices), function ($collection) {
                $pricing = collect($this->bundlePrices)->map(function ($item) {
                    return [
                        'quantity' => $item['quantity'],
                        'unitPrice' => number_format($item['unitPrice']/100, 2)
                    ];
                });
                return $collection->put('bundlePrices', $pricing->all());
            })
            ->all();
    }
}
