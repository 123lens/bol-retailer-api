<?php
namespace Budgetlens\BolRetailerApi;

class SharedClient extends BaseClient
{
    protected $endpoint = 'https://api.bol.com/shared';
    protected $endpointTest = 'https://api.bol.com/shared-demo';

    /**
     * Initialize available endpoints
     */
    public function initializeEndpoints(): void
    {
    }
}
