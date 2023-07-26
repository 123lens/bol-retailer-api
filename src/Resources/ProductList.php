<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class ProductList extends BaseResource
{
    public $products;
    public $sort;

    public function setProductsAttribute($value): self
    {
        if (is_array($value)) {
            $items  = new Collection();

            foreach ($value as $product) {
                $items->push(new Product($product));
            }

            $this->products = $items;
        }

        return $this;
    }
}
