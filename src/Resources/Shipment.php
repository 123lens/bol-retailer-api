<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Shipment\ShipmentItem;
use Illuminate\Support\Collection;

class Shipment extends BaseResource
{
    public $shipmentId;
    public $shipmentDateTime;
    public $shipmentReference;
    public $pickUpPoint;
    public $order;
    public $shipmentDetails;
    public $billingDetails;
    public $shipmentItems;
    public $transport;

    public function setPickUpPointAttribute($value): self
    {
        $this->pickUpPoint = (bool) $value;

        return $this;
    }
    /**
     * Set Shipment Details Attribute
     * @param $value
     * @return $this
     */
    public function setShipmentDetailsAttribute($value)
    {
        if (!$value instanceof Address) {
            $value = new Address($value);
        }

        $this->shipmentDetails = $value;

        return $this;
    }

    /**
     * Set Billing Details Attribute
     * @param $value
     * @return $this
     */
    public function setBillingDetailsAttribute($value): self
    {
        if (!$value instanceof Address) {
            $value = new Address($value);
        }

        $this->billingDetails = $value;

        return $this;
    }

    /**
     * Set Shipment Date Attribute
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setShipmentDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->shipmentDateTime = $value;

        return $this;
    }

    /**
     * Set Order Attribute
     *
     * @param $value
     * @return $this
     */
    public function setOrderAttribute($value): self
    {
        if (!$value instanceof Order) {
            $value = new Order($value);
        }

        $this->order = $value;

        return $this;
    }
    /**
     * Set Shipment Items Attribute
     * @param $value
     * @return $this
     */
    public function setShipmentItemsAttribute($value): self
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            $items->push(new ShipmentItem($item));
        });
        $this->shipmentItems = $items;

        return $this;
    }

    /**
     * Set Transport Attribute
     * @param $value
     * @return $this
     */
    public function setTransportAttribute($value): self
    {
        if (!$value instanceof Transport) {
            $value = new Transport($value);
        }

        $this->transport = $value;

        return $this;
    }
}
