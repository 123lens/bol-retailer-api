<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class Order extends BaseResource
{
    public $orderId;
    public $pickUpPoint;
    public $shipmentDetails;
    public $billingDetails;
    public $orderPlacedDateTime;
    public $orderItems;

    public function __construct($attributes = [])
    {
        $this->orderItems = [];
        parent::__construct($attributes);
    }

    public function setShipmentDetailsAttribute($value)
    {
        if (!$value instanceof Address) {
            $value = new Address($value);
        }

        $this->shipmentDetails = $value;

        return $this;
    }

    public function setBillingDetailsAttribute($value)
    {
        if (!$value instanceof Address) {
            $value = new Address($value);
        }

        $this->billingDetails = $value;

        return $this;
    }

    /**
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setOrderPlacedDateTimeAttribute($value)
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }
        $this->orderPlacedDateTime = $value;

        return $this;
    }

    public function setOrderItemsAttribute($value)
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            $items->push(new OrderItem($item));
        });
        $this->orderItems = $items;
    }
}
