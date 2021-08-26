<?php
namespace Budgetlens\BolRetailerApi\Resources\InvoiceDetailed;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Item extends BaseResource
{
    public $Description;
    public $Name;
    public $ClassifiedTaxCategory;


    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setDescriptionAttribute($value): self
    {
        if (is_array($value)) {
            $value = array_shift($value);
        }

        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->Description = $value;

        return $this;
    }

    public function setNameAttribute($value): self
    {
        if (is_object($value)) {
            $value = $value->value ?? null;
        }

        $this->Name = $value;

        return $this;
    }

    public function setClassifiedTaxCategoryAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof ClassifiedTaxCategory) {
                $item = new ClassifiedTaxCategory($item);
            }
            $items->push($item);
        });

        $this->ClassifiedTaxCategory = $items;

        return $this;
    }
}
