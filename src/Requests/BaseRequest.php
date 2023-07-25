<?php

namespace Budgetlens\BolRetailerApi\Requests;

abstract class BaseRequest
{
    private $headers = [];
    /**
     * BaseResource constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->fill($attributes);
    }


    public function getParameters(): array
    {
        return [];
    }

    public function getHeaders(): array
    {
        return [];
    }

    public function addHeader(string $name, mixed $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }
}
