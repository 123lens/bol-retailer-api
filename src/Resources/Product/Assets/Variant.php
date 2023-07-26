<?php
namespace Budgetlens\BolRetailerApi\Resources\Product\Assets;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Variant extends BaseResource
{
    public null|string $size;
    public null|int $width;
    public null|int $height;
    public null|string $mimeType;
    public null|string $url;
}
