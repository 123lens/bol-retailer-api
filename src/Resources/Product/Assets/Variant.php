<?php
namespace Budgetlens\BolRetailerApi\Resources\Product\Assets;

use Budgetlens\BolRetailerApi\Resources\BaseResource;
use Illuminate\Support\Collection;

class Variant extends BaseResource
{
    public ?string $size;
    public ?int $width;
    public ?int $height;
    public ?string $mimeType;
    public ?string $url;
}
