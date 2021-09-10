<?php
namespace Budgetlens\BolRetailerApi\Resources\Insights;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Insights\Performance\Norm;
use Budgetlens\BolRetailerApi\Resources\Insights\Performance\Score;

class PerformanceDetail extends BaseResource
{
    public $week;
    public $year;
    public $score;
    public $norm;

    public function setPeriodAttribute($value): self
    {
        $this->week = (int)$value->week ?? null;
        $this->year = (int)$value->year ?? null;

        return $this;
    }

    public function setScoreAttribute($value): self
    {
        $this->score = new Score($value);

        return $this;
    }

    public function setNormAttribute($value): self
    {
        $this->norm = new Norm($value);

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->score), function ($collection) {
                return $collection->put('score', $this->score->toArray());
            })
            ->when(!is_null($this->norm), function ($collection) {
                return $collection->put('norm', $this->norm->toArray());
            })
            ->reject(function ($value) {
                return is_null($value);
            })
            ->all();
    }
}
