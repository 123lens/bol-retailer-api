<?php
namespace Budgetlens\BolRetailerApi\Contracts;


interface Config
{
    public function getClientId(): string;
    public function getClientSecret(): string;
    public function getEndpoint(): string;
    public function getApiVersionHeader(): string;
    public function getMiddleware(): array;
    public function cacheToken(): bool;
}
