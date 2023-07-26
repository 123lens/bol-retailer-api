<?php
namespace Budgetlens\BolRetailerApi\Contracts;

interface Request
{
    public function addQuery(string $key, mixed $value): self;
    public function addHeader(string $name, mixed $value): self;

    public function getParameters(): array;
    public function getQuery(): array;
    public function getHeaders(): array;
}
