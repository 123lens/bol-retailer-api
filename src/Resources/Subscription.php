<?php
namespace Budgetlens\BolRetailerApi\Resources;

class Subscription extends BaseResource
{
    public $id;
    public $resources;
    public $url;

    public function setResourcesAttribute($value): self
    {
        $this->resources = collect($value);

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->reject(function ($value) {
                return is_null($value);
            })
            ->all();
    }
}
