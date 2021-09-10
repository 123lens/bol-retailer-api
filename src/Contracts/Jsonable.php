<?php
namespace Budgetlens\BolRetailerApi\Contracts;

interface Jsonable
{
    public function toJson(int $options = 0): string;
}
