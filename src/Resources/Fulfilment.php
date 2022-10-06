<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Types\DeliveryCodes;

class Fulfilment extends BaseResource
{
    public $method;
    public $deliveryCode;
    public $distributionParty;
    public $latestDeliveryDate;
    public $exactDeliveryDate;
    public $expiryDate;
    public $pickUpPoints;
    public $timeFrameType;
    public $status;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setLatestDeliveryDateAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->latestDeliveryDate = $value;

        return $this;
    }

    public function setExactDeliveryDateAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->exactDeliveryDate = $value;

        return $this;
    }

    public function setExpiryDateAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->expiryDate = $value;

        return $this;
    }

    /**
     * To Array
     * @return array
     */
    public function toArray(): array
    {
        return collect(parent::toArray())
            ->map(function ($item) {
                if ($item instanceof \DateTime) {
                    return $item->format('c');
                }

                return $item;
            })
            ->all();
    }
}
