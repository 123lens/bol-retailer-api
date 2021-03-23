<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\InvoiceItem as InvoiceResource;
use Illuminate\Support\Collection;

class Invoice extends BaseResource
{
    public $invoiceListItems;
    public $period;
    public $type;
    public $contents;



    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * Set Invoice Items
     * @param $value
     * @return $this
     */
    public function setInvoiceListItemsAttribute($value): self
    {
        $collection = new Collection();

        collect($value)->each(function ($item) use ($collection) {
            $collection->push(new InvoiceItem($item));
        });

        $this->invoiceListItems = $collection;

        return $this;
    }

    /**
     * Set Period Attribute
     * @param $value
     * @return $this
     */
    public function setPeriodAttribute($value): self
    {
        list ($from, $till) = explode("/", $value);
        $this->period = collect([
            'from' => $from,
            'till' => $till
        ])->map(function ($value) {
            return new \DateTime($value);
        })
        ->all();

        return $this;
    }
}
