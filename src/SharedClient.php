<?php
namespace Budgetlens\BolRetailerApi;

use Budgetlens\BolRetailerApi\Endpoints\Status;

class SharedClient extends BaseClient
{
    protected $endpoint = 'https://api.bol.com/shared';
    protected $endpointTest = 'https://api.bol.com/shared-demo';

    /** @var  Status */
    public $status;

    /**
     * Initialize available endpoints
     */
    public function initializeEndpoints(): void
    {
        $this->status = new Status($this);
    }
}
