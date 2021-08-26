<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class InvoiceLine extends BaseResource
{
    public $ID;
    public $InvoicedQuantity;
    public $LineExtensionAmount;
    public $TaxTotal;
    public $Item;
    public $Price;


    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setIDAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->ID = $value;

        return $this;
    }

    public function setInvoicedQuantityAttribute($value): self
    {
        if (!$value instanceof Quantity) {
            $value = new Quantity($value);
        }

        $this->InvoicedQuantity = $value;

        return $this;
    }

    public function setLineExtensionAmountAttribute($value): self
    {
        if (!$value instanceof Currency) {
            $value = new Currency($value);
        }

        $this->LineExtensionAmount = $value;

        return $this;
    }

    public function setTaxTotalAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof TaxTotal) {
                $item = new TaxTotal($item);
            }
            $items->push($item);
        });

        $this->TaxTotal = $items;

        return $this;
    }

    public function setItemAttribute($value): self
    {
        if (!$value instanceof Item) {
            $value = new Item($value);
        }

        $this->Item = $value;

        return $this;
    }

    public function setPriceAttribute($value): self
    {
        if (!$value instanceof Price) {
            $value = new Price($value);
        }

        $this->Price = $value;

        return $this;
    }
}
