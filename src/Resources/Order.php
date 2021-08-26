<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Order\OrderItem;
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

    public function setPickupPointAttribute($value): self
    {
        $this->pickUpPoint = (bool)$value;

        return $this;
    }

    public function setShipmentDetailsAttribute($value): self
    {
        if (!$value instanceof Address) {
            $value = new Address($value);
        }

        $this->shipmentDetails = $value;

        return $this;
    }

    public function setBillingDetailsAttribute($value): self
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
    public function setOrderPlacedDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }
        $this->orderPlacedDateTime = $value;

        return $this;
    }

    public function setOrderItemsAttribute($value): self
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof OrderItem) {
                $item = new OrderItem($item);
            }

            $items->push(new OrderItem($item));
        });

        $this->orderItems = $items;

        return $this;
    }
}
