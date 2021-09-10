<?php
namespace Budgetlens\BolRetailerApi\Resources\Returns;

use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Item extends BaseResource
{
    public $rmaId;
    public $orderId;
    public $ean;
    public $title;
    public $expectedQuantity;
    public $returnReason;
    public $trackAndTrace;
    public $transporterName;
    public $handled;
    public $processingResults;
    public $customerDetails;


    public function setExpectedQuantityAttribute($value): self
    {
        $this->expectedQuantity = (int)$value;

        return $this;
    }

    public function setCustomerDetailsAttribute($value): self
    {
        if (!$value instanceof Address) {
            $value = new Address($value);
        }

        $this->customerDetails = $value;

        return $this;
    }

    public function setHandledAttribute($value): self
    {
        $this->handled = (bool)$value;

        return $this;
    }

    public function setReturnReasonAttribute($value): self
    {
        if (!$value instanceof ReturnReason) {
            $value = new ReturnReason($value);
        }

        $this->returnReason = $value;

        return $this;
    }

    public function setProcessingResultsAttribute($value)
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof ProcessingResult) {
                $item = new ProcessingResult($item);
            }
            $items->push($item);
        });

        $this->processingResults = $items;

        return $this;
    }
}
