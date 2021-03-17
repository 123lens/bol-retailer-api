<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Types\DeliveryCodes;

class Fulfilment extends BaseResource
{
    public $method;
    public $deliveryCode;
    public $distributionParty;
    public $latestDeliveryDate;
    public $expiryDate;
    public $pickUpPoints;


    public function __construct($attributes = [])
    {
        $this->setDefaults();

        parent::__construct($attributes);
    }

    /**
     * Set Default
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->deliveryCode = DeliveryCodes::DEFAULT;

        return $this;
    }

    /**
     * To Array
     * @return array
     */
    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when($this->method === 'FBB', function ($collection) {
                return $collection->forget('deliveryCode');
            })
            ->all();
    }
}
