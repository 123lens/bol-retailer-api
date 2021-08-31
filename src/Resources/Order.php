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

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(count($this->orderItems), function ($collection) {
                $items = collect($this->orderItems)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('orderItems', $items->all());
            })
            ->when(!is_null($this->shipmentDetails), function ($collection) {
                return $collection
                    ->forget('shipmentDetails')
                    ->put('shipmentDetails', $this->shipmentDetails->toArray());
            })
            ->when(!is_null($this->billingDetails), function ($collection) {
                return $collection
                    ->forget('billingDetails')
                    ->put('billingDetails', $this->billingDetails->toArray());
            })
            ->reject(function ($value) {
                return is_null($value) || (is_countable($value) && !count($value));
            })
            ->map(function ($item) {
                if ($item instanceof \DateTime) {
                    return $item->format('c');
                }

                return $item;
            })
            ->all();
    }
}
