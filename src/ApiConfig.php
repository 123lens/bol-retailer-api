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

    /**
     * @note: As of v7 decprecated!
     * @return string
     */
    public function getEndpoint(): string
    {
        return 'https://api.bol.com/retailer';
    }

    public function getTestMode(): bool
    {
        $testMode = getenv('TEST_MODE');

        return (bool) $testMode ?? false;
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

    public function getTimeout(): int
    {
        return 180;
    }

    public function getUserAgent(): string
    {
        return '';
    }
}
