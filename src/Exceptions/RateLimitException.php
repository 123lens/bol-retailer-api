<?php
namespace Budgetlens\BolRetailerApi\Exceptions;

use Throwable;

class RateLimitException extends \Exception
{
    private $retry;

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Rate limit', $code, $previous);
        $this->retry = $message;
    }

    /**
     * @return int
     */
    public function getRetryAfter(): int
    {
        return $this->retry ?? 0;
    }
}
