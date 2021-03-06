<?php
namespace Budgetlens\BolRetailerApi\Resources\Replenishment;

use Budgetlens\BolRetailerApi\Resources\BaseResource;

class Line extends BaseResource
{
    public $ean;
    public $type;
    public $lineState;
    public $quantityAnnounced;
    public $quantityReceived;
    public $quantityInProgress;
    public $quantityWithGradedState;
    public $quantityWithRegularState;

    public function setQuantityAttribute($value): self
    {
        $this->quantity = (int)$value;

        return $this;
    }
}
