<?php
namespace Budgetlens\BolRetailerApi\Contracts;

interface Request
{
    public function getParameters(): array;
    public function getHeaders(): array;
}
