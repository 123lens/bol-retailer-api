<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class Inbound extends BaseResource
{
    public $inboundId;
    public $reference;
    public $creationDateTime;
    public $state;
    public $labellingService;
    public $announcedBSKUs;
    public $announcedQuantity;
    public $receivedBSKUs;
    public $receivedQuantity;
    public $timeSlot;
    public $inboundTransporter;
    public $products;

    public function __construct($attributes = [])
    {
        $this->setDefaults();

        parent::__construct($attributes);
    }

    public function setProductsAttribute($value)
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Product) {
                $item = new Product($item);
            }
            $items->push($item);
        });
        $this->products = $items;

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
    /**
     * Set Timeslot
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function setTimeSlotAttribute($value): self
    {
        if (!$value instanceof Timeslot) {
            $value = new Timeslot($value);
        }

        $this->timeSlot = $value;

        return $this;
    }

    /**
     * Set Inbound Transporter
     * @param $value
     * @return $this
     */
    public function setInboundTransporterAttribute($value): self
    {
        if (!$value instanceof Transporter) {
            $value = new Transporter($value);
        }

        $this->inboundTransporter = $value;

        return $this;
    }

    /**
     * Set Defaults
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->labellingService = false;

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->products), function ($collection) {
                $products = collect($this->products)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('products', $products->all());
            })
            ->when(!is_null($this->inboundTransporter), function ($collection) {
                return $collection->put('inboundTransporter', $this->inboundTransporter->toArray());
            })
            ->when(!is_null($this->timeSlot), function ($collection) {
                return $collection->put('timeSlot', $this->timeSlot->toArray());
            })
            ->reject(function ($value) {
                return is_null($value);
            })
            ->all();
    }
}
