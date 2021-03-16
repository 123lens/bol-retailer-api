<?php
namespace Budgetlens\BolRetailerApi\Resources;

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

    public function setPriceAttribute($value): void
    {
        if ($value instanceof Money) {
            $this->price = $value;
        } else {
            $this->price = new Money($value);
        }
    }
}
