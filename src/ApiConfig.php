<?php
namespace Budgetlens\BolRetailerApi;


use Budgetlens\BolRetailerApi\Contracts\Config;

class ApiConfig implements Config
{
    private $middleware = [];

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

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    public function addMiddleware($middleware)
    {
        $this->middleware[] = $middleware;
    }

    public function cacheToken(): bool
    {
        return true;
    }
}
