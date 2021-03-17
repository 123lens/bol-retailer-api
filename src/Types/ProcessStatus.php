<?php
namespace Budgetlens\BolRetailerApi\Types;

class ProcessStatus
{
    const PENDING = "PENDING";
    const SUCCESS = "SUCCESS";
    const FAILURE = "FAILURE";
    const TIMEOUT = "TIMEOUT";

    // default status
    const DEFAULT = "PENDING";
}
