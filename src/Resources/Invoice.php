<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Invoice\InvoiceItem;
use Budgetlens\BolRetailerApi\Resources\Invoice\Period;
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

        $this->period = new Period([
            'start' => $from,
            'end' => $till
        ]);

        return $this;
    }
}
