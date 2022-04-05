<?php
namespace Budgetlens\BolRetailerApi;

use Budgetlens\BolRetailerApi\Contracts\Config;
use Budgetlens\BolRetailerApi\Endpoints\Commissions;
use Budgetlens\BolRetailerApi\Endpoints\Inbounds;
use Budgetlens\BolRetailerApi\Endpoints\Insights;
use Budgetlens\BolRetailerApi\Endpoints\Inventory;
use Budgetlens\BolRetailerApi\Endpoints\Invoices;
use Budgetlens\BolRetailerApi\Endpoints\Offers;
use Budgetlens\BolRetailerApi\Endpoints\Replenishments;
use Budgetlens\BolRetailerApi\Endpoints\Returns;
use Budgetlens\BolRetailerApi\Endpoints\Shipments;
use Budgetlens\BolRetailerApi\Endpoints\Shipping;
use Budgetlens\BolRetailerApi\Endpoints\Status;
use Budgetlens\BolRetailerApi\Endpoints\Subscriptions;
use Budgetlens\BolRetailerApi\Endpoints\Transports;
use Budgetlens\BolRetailerApi\Endpoints\Orders;

class Client extends BaseClient implements ApiClient
{
    protected $endpoint = 'https://api.bol.com/retailer';
    protected $endpointTest = 'https://api.bol.com/retailer-demo';


    /** @var Commissions */
    public $commission;

    /** @var Insights */
    public $insights;

    /** @var  Inventory */
    public $inventory;

    /** @var  Invoices */
    public $invoices;

    /** @var Offers */
    public $offers;

    /** @var Orders */
    public $orders;

    /** @var  Status */
    public $status;

    // product-content

    /** @var  Replenishments */
    public $replenishments;

    /** @var  Returns */
    public $returns;

    /** @var  Shipping */
    public $shipping;

    /** @var  Shipments */
    public $shipments;

    /** @var  Inbounds */
    public $inbounds;

    /** @var Subscriptions */
    public $subscriptions;

    /** @var  Transports */
    public $transports;

    /**
     * Initialize available endpoints
     */
    public function initializeEndpoints(): void
    {
        $this->commission = new Commissions($this);
        $this->insights = new Insights($this);
        $this->orders = new Orders($this);
        $this->offers = new Offers($this);
        $this->status = new Status($this);
        $this->replenishments = new Replenishments($this);
        $this->returns = new Returns($this);
        $this->shipping = new Shipping($this);
        $this->shipments = new Shipments($this);
        $this->inventory = new Inventory($this);
        $this->invoices = new Invoices($this);
        $this->inbounds = new Inbounds($this);
        $this->subscriptions = new Subscriptions($this);
        $this->transports = new Transports($this);
    }
}
