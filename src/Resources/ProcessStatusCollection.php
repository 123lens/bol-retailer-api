<?php
namespace Budgetlens\BolRetailerApi\Resources;

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
            foreach ($value as $item) {
                $this->processStatuses[] = new ProcessStatus($item);
            }
        }

        return $this;
    }
}
