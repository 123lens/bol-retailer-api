<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Budgetlens\BolRetailerApi\Resources\Replenishment\DeliveryInformation;
use Budgetlens\BolRetailerApi\Resources\Replenishment\Line;
use Budgetlens\BolRetailerApi\Resources\Replenishment\LoadCarrier;
use Budgetlens\BolRetailerApi\Resources\Replenishment\StateTransition;
use Budgetlens\BolRetailerApi\Resources\Returns\Item;
use Illuminate\Support\Collection;

class Returns extends BaseResource
{
    public $returnId;
    public $registrationDateTime;
    public $fulfilmentMethod;
    public $returnItems;

    public function __construct($attributes = [])
    {
        $this->returnItems = [];

        parent::__construct($attributes);
    }

    public function setRegistrationDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->registrationDateTime = $value;

        return $this;
    }

    public function setReturnItemsAttribute($value): self
    {
        $items = new Collection();

        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Item) {
                $item = new Item($item);
            }
            $items->push($item);
        });

        $this->returnItems = $items;

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
