<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class Transport extends BaseResource
{
    public $transportId;
    public $transporterCode;
    public $trackAndTrace;
    public $shippingLabelId;
    public $transportEvents;

    public function setTransportEventsAttribute($value): self
    {
        $events = new Collection();

        collect($value)->each(function ($item) use ($events) {
            $events->push(new TransportEvent($item));
        });

        $this->transportEvents = $events;

        return $this;
    }
}
