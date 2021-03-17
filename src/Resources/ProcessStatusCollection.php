<?php
namespace Budgetlens\BolRetailerApi\Resources;

use Illuminate\Support\Collection;

class ProcessStatusCollection extends BaseResource
{
    public $processStatuses;

    public function __construct($attributes = [])
    {
        $this->setDefaults();

        parent::__construct($attributes);
    }

    /**
     * Set Defaults
     *
     * @return $this
     */
    public function setDefaults(): self
    {
        $this->processStatuses = [];

        return $this;
    }

    /**
     * Set Process Statusses
     * @param $value
     * @return $this
     */
    public function setProcessStatusesAttribute($value): self
    {
        if (is_array($value)) {
            $items = new Collection();
            collect($value)->each(function ($item) use ($items) {
                $items->push(new ProcessStatus($item));
            });
            $this->processStatuses = $items;
        }

        return $this;
    }
}
