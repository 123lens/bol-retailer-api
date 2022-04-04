<?php
namespace Budgetlens\BolRetailerApi;

class AdvertisingClient extends BaseClient
{
    protected $endpoint = 'https://api.bol.com/advertiser';
    protected $endpointTest = 'https://api.bol.com/advertiser-demo';

    /**
     * Initialize available endpoints
     */
    public function initializeEndpoints(): void
    {
    }
}
