<?php
namespace Budgetlens\BolRetailerApi\Contracts;

interface Config
{
    public function getClientId(): string;
    public function getClientSecret(): string;
    /**
     * @note: Per v7 deprecated
        public function getEndpoint(): string;
     */
    public function getTestMode(): bool;
    public function getMiddleware(): array;
    public function cacheToken(): bool;
    public function getTimeout(): int;
    public function getUserAgent(): string;
}
