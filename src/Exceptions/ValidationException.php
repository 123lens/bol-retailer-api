<?php
namespace Budgetlens\BolRetailerApi\Exceptions;

use Throwable;

class ValidationException extends \Exception
{
    private $violations = [];

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $this->setViolations($message);

        parent::__construct("Validation Failed, See violations", $code, $previous);
    }

    public function setViolations($violations)
    {
        if (is_array($violations)) {
            $this->violations = collect($violations);
        }
    }

    public function getViolations()
    {
        return $this->violations;
    }
}
