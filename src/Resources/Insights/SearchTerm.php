<?php
namespace Budgetlens\BolRetailerApi\Resources\Insights;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Insights\Search\RelatedTerm;
use Illuminate\Support\Collection;

class SearchTerm extends BaseResource
{
    public $searchTerm;
    public $type;
    public $total;
    public $countries;
    public $periods;
    public $relatedSearchTerms;

    public function __construct($attributes = [])
    {
        $this->total = 0;
        $this->countries = [];
        $this->periods = [];
        $this->relatedSearchTerms = [];

        parent::__construct($attributes);
    }

    public function setCountriesAttribute($value): self
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Country) {
                $item = new Country($item);
            }
            $items->push($item);
        });

        $this->countries = $items;

        return $this;
    }

    public function setPeriodsAttribute($value): self
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof Period) {
                $item = new Period($item);
            }
            $items->push($item);
        });

        $this->periods = $items;

        return $this;
    }

    public function setRelatedSearchTermsAttribute($value): self
    {
        $items = new Collection();
        collect($value)->each(function ($item) use ($items) {
            if (!$item instanceof RelatedTerm) {
                $item = new RelatedTerm($item);
            }
            $items->push($item);
        });

        $this->relatedSearchTerms = $items;

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(count($this->countries), function ($collection) {
                $countries = collect($this->countries)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('countries', $countries->all());
            })
            ->when(count($this->periods), function ($collection) {
                $periods = collect($this->periods)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('periods', $periods->all());
            })
            ->when(count($this->relatedSearchTerms), function ($collection) {
                $terms = collect($this->periods)->map(function ($item) {
                    return $item->toArray();
                });
                return $collection->put('relatedSearchTerms', $terms->all());
            })
            ->reject(function ($value) {
                return is_null($value);
            })
            ->all();
    }
}
