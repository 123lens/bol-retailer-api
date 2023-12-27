<?php

namespace Budgetlens\BolRetailerApi\Requests;

use Budgetlens\BolRetailerApi\Contracts\Arrayable;
use Budgetlens\BolRetailerApi\Contracts\Jsonable;
use Budgetlens\BolRetailerApi\Contracts\Request;
use Budgetlens\BolRetailerApi\Exceptions\JsonEncodingException;
use Budgetlens\BolRetailerApi\Resources\Concerns\HasAttributes;
use Budgetlens\BolRetailerApi\Support\Str;
use JsonSerializable;

abstract class BaseRequest implements Request, Arrayable, Jsonable, JsonSerializable
{
    use HasAttributes;

    private $headers = [];
    private $queryParameters = [];

    /**
     * BaseResource constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->fill($attributes);
    }

    public function addQuery(string $key, mixed $value): self
    {
        $this->queryParameters[$key] = $value;

        return $this;
    }

    public function getParameters(): array
    {
        return $this->toArray();
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getQuery(): array
    {
        return collect($this->queryParameters)
            ->map(function ($data, $key) {
                return [Str::snake($key, '-') => $data];
            })
            ->collapse()
            ->reject(function ($value) {
                return $value === null;
            })
            ->all();
    }

    public function addHeader(string $name, mixed $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Json Serialize
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Output to array
     * @return array
     */
    public function toArray(): array
    {
        return collect($this->attributesToArray())
            ->except('headers')
            ->reject(function ($value) {
                return $value === null;
            })
            ->all();
    }

    /**
     * Output as json
     * @param int $options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forResource($this, json_last_error_msg());
        }

        return $json;
    }

    /**
     * Dynamically retrieve attributes on the resource.
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the resource.
     * @param string $key
     * @param $value
     */
    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }
}
