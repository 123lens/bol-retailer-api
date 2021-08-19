<?php
namespace Budgetlens\BolRetailerApi\Types;

final class ReplenishState
{
    const DRAFT = "DRAFT";
    const ANNOUNCED = "ANNOUNCED";
    const IN_TRANSIT = "IN_TRANSIT";
    const ARRIVED_AT_WH = "ARRIVED_AT_WH";
    const IN_PROGRESS_AT_WH = "IN_PROGRESS_AT_WH";
    const CANCELLED = "CANCELLED";
    const DONE = "DONE";
}
