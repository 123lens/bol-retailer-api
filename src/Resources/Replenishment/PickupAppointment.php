<?php
namespace Budgetlens\BolRetailerApi\Resources\Replenishment;

use Budgetlens\BolRetailerApi\Resources\Address;
use Budgetlens\BolRetailerApi\Resources\BaseResource;

class PickupAppointment extends BaseResource
{
    public $address;
    public $pickupTimeSlot;
    public $commentToTransporter;

    public function setAddressAttribute($value): self
    {
        if (!$value instanceof Address) {
            $value = new Address($value);
        }

        $this->address = $value;

        return $this;
    }

    public function setPickupTimeSlotAttribute($value): self
    {
        if (!$value instanceof PickupTimeslot) {
            $value = new PickupTimeslot($value);
        }

        $this->pickupTimeSlot = $value;

        return $this;
    }


    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->address), function ($collection) {
                return $collection->put('address', $this->address->toArray());
            })
            ->when(!is_null($this->pickupTimeSlot), function ($collection) {
                return $collection->put('pickupTimeSlot', $this->pickupTimeSlot->toArray());
            })
            ->all();
    }
}
