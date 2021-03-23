<?php
namespace Budgetlens\BolRetailerApi\Types;

class CancelReasonCodes
{
    const OUT_OF_STOCK = "OUT_OF_STOCK";
    const REQUESTED_BY_CUSTOMER = "REQUESTED_BY_CUSTOMER";
    const BAD_CONDITION = "BAD_CONDITION";
    const HIGHER_SHIPCOST = "HIGHER_SHIPCOST";
    const INCORRECT_PRICE = "INCORRECT_PRICE";
    const NOT_AVAIL_IN_TIME = "NOT_AVAIL_IN_TIME";
    const NO_BOL_GUARANTEE = "NO_BOL_GUARANTEE";
    const ORDERED_TWICE = "ORDERED_TWICE";
    const RETAIN_ITEM = "RETAIN_ITEM";
    const TECH_ISSUE = "TECH_ISSUE";
    const UNFINDABLE_ITEM = "UNFINDABLE_ITEM";
    const OTHER = "OTHER";

    // default
    const DEFAULT = "REQUESTED_BY_CUSTOMER";
}
