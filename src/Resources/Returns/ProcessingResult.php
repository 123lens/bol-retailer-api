<?php
namespace Budgetlens\BolRetailerApi\Resources\Returns;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class ProcessingResult extends BaseResource
{
    public $quantity;
    public $processingResult;
    public $handlingResult;
    public $processingDateTime;

    public function setQuantityAttribute($value): self
    {
        $this->quantity = (int)$value;

        return $this;
    }

    public function setProcessingDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->processingDateTime = $value;

        return $this;
    }
}
