<?php
namespace Budgetlens\BolRetailerApi;


use Budgetlens\BolRetailerApi\Contracts\Config;

class ApiConfig implements Config
{
    public function getClientId(): string
    {
        return getenv('CLIENT_ID');
    }

    public function getClientSecret(): string
    {
        return getenv('CLIENT_SECRET');
    }

    public function getEndpoint(): string
    {
        return 'https://api.bol.com/retailer';
    }

    public function getApiVersionHeader(): string
    {
        return 'application/vnd.retailer.v4+json';
    }

    public function getMiddleware(): array
    {
        return [];
    }

    public function cacheToken(): bool
    {
        return true;
    }
}
