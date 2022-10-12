<?php
namespace Budgetlens\BolRetailerApi\Resources\Order;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Budgetlens\BolRetailerApi\Resources\Fulfilment;
use Budgetlens\BolRetailerApi\Resources\Offer;
use Budgetlens\BolRetailerApi\Resources\Product;

class OrderItem extends BaseResource
{
    public $orderItemId;
    public $cancellationRequest;
    // fulfilment is set when retrieving order-details
    public $fulfilment;
    // fulfilmentMethod & fulfilmentStatus are set when retrieving list of orders ...
    // we're using fulfilment, so cast attributes to fulfilment
    public $latestChangedDateTime;
    public $offer;
    public $product;
    public $ean;
    public $quantity;
    public $quantityShipped;
    public $quantityCancelled;
    public $unitPrice;
    public $commission;

    public function setCancellationRequestAttribute($value): self
    {
        $this->cancellationRequest = (bool)$value;

        return $this;
    }

    public function setLatestChangedDateTimeAttribute($value): self
    {
        if (!$value instanceof \DateTime) {
            $value = new \DateTime($value);
        }

        $this->latestChangedDateTime = $value;

        return $this;
    }

    public function setFulfilmentAttribute($value): self
    {
        if (!$value instanceof Fulfilment) {
            $value = new Fulfilment($value);
        }

        $this->fulfilment = $value;

        return $this;
    }

    public function setFulfilmentMethodAttribute($value): self
    {
        if (is_null($this->fulfilment)) {
            $this->setFulfilmentAttribute([
                'method' => $value
            ]);
        } else {
            $this->fulfilment->method = $value;
        }

        return $this;
    }

    public function setFulfilmentStatusAttribute($value): self
    {
        if (is_null($this->fulfilment)) {
            $this->setFulfilmentAttribute([
                'status' => $value
            ]);
        } else {
            $this->fulfilment->status = $value;
        }

        return $this;
    }

    public function setOfferAttribute($value): self
    {
        if (!$value instanceof Offer) {
            $value = new Offer($value);
        }

        $this->offer = $value;

        return $this;
    }

    public function setProductAttribute($value): self
    {
        if (!$value instanceof Product) {
            $value = new Product($value);
        }

        $this->product = $value;

        return $this;
    }

    public function setQuantityAttribute($value): self
    {
        $this->quantity = (int)$value;

        return $this;
    }

    public function setQuantityShippedAttribute($value): self
    {
        $this->quantityShipped = (int)$value;

        return $this;
    }

    public function setQuantityCancelledAttribute($value): self
    {
        $this->quantityCancelled = (int)$value;

        return $this;
    }

    public function toArray(): array
    {
        return collect(parent::toArray())
            ->when(!is_null($this->fulfilment), function ($collection) {
                return $collection
                    ->forget('fulfilment')
                    ->put('fulfilment', $this->fulfilment->toArray());
            })
            ->when(!is_null($this->offer), function ($collection) {
                return $collection
                    ->forget('offer')
                    ->put('offer', $this->offer->toArray());
            })
            ->when(!is_null($this->product), function ($collection) {
                return $collection
                    ->forget('product')
                    ->put('product', $this->product->toArray());
            })
            ->map(function ($item) {
                if ($item instanceof \DateTime) {
                    return $item->format('c');
                }

                return $item;
            })
            ->reject(function ($value) {
                return is_null($value);
            })
            ->all();
    }
}
