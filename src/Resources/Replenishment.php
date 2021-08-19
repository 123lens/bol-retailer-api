<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Replenishment\DeliveryInformation;
use Budgetlens\BolRetailerApi\Resources\Replenishment\Line;
use Budgetlens\BolRetailerApi\Resources\Replenishment\LoadCarrier;
use Budgetlens\BolRetailerApi\Resources\Replenishment\StateTransition;
use Illuminate\Support\Collection;

class Replenishment extends BaseResource
{
    public $replenishmentId;
    public $reference;
    public $creationDateTime;
    public $lines;
    public $invalidLines;
    public $labelingByBol;
    public $state;
    public $deliveryInformation;
    public $numberOfLoadCarriers;
    public $loadCarriers;
    public $stateTransitions;


    public function __construct($attributes = [])
    {
        $this->labelingByBol = false;
        $this->lines = [];
        $this->invalidLines = [];
        $this->loadCarriers = [];
        $this->stateTransitions = [];

        parent::__construct($attributes);
    }

    public function setReferenceAttribute($value): self
    {
        $this->reference = strtoupper($value);

        return $this;
    }

    public function setDeliveryInformationAttribute($value): self
    {
        if (!$value instanceof DeliveryInformation) {
            $value = new DeliveryInformation($value);
        }

        $this->deliveryInformation = $value;

        return $this;
    }

    public function setLinesAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Line) {
                $item = new Line($item);
            }
            $items->push($item);
        });
        $this->lines = $items;

        return $this;
    }

    public function setInvalidLinesAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Line) {
                $item = new Line($item);
            }
            $items->push($item);
        });
        $this->invalidLines = $items;

        return $this;
    }

    public function setLoadCarriersAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof LoadCarrier) {
                $item = new LoadCarrier($item);
            }
            $items->push($item);
        });

        $this->loadCarriers = $items;

        return $this;
    }

    public function setStateTransitionsAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof StateTransition) {
                $item = new StateTransition($item);
            }
            $items->push($item);
        });

        $this->stateTransitions = $items;

        return $this;
    }


    /**
     * Set Creation DateTime
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setCreationDateTimeAttribute($value): self
    {
        $this->creationDateTime = new \DateTime($value);

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(count($this->lines), function ($collection) {
                $lines = collect($this->lines)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('lines', $lines->all());
            })
            ->when(count($this->invalidLines), function ($collection) {
                $lines = collect($this->invalidLines)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('invalidLines', $lines->all());
            })
            ->when(count($this->loadCarriers), function ($collection) {
                $carriers = collect($this->loadCarriers)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('loadCarriers', $carriers->all());
            })
            ->when(!is_null($this->deliveryInformation), function ($collection) {
                return $collection
                    ->forget('deliveryInformation')
                    ->put('deliveryInfo', $this->deliveryInformation->toArray());
            })
            ->when(count($this->stateTransitions), function ($collection) {
                $transitions = collect($this->stateTransitions)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('stateTransitions', $transitions->all());
            })
            ->reject(function ($value) {
                return is_null($value) || (is_countable($value) && !count($value));
            })
            ->all();
    }
}
