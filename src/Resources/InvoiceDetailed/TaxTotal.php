<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class TaxTotal extends BaseResource
{
    public $TaxAmount;
    public $TaxSubtotal;


    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setTaxAmountAttribute($value): self
    {
        if (!$value instanceof Currency) {
            $value = new Currency($value);
        }

        $this->TaxAmount = $value;

        return $this;
    }

    public function setTaxSubtotalAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof TaxSubTotal) {
                $item = new TaxSubTotal($item);
            }
            $items->push($item);
        });

        $this->TaxSubtotal = $items;

        return $this;
    }

    public function setTaxCategoryAttribute($value): self
    {
        if (!$value instanceof TaxCategory) {
            $value = new TaxCategory($value);
        }

        $this->TaxCategory = $value;

        return $this;
    }
}
