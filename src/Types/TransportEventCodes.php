<?php
namespace Budgetlens\BolRetailerApi\Types;

/**
 * @todo update class to enum
 */
class TransportEventCodes
{
    const PRE_ANNOUNCED = "PRE_ANNOUNCED";
    const AT_TRANSPORTER = "AT_TRANSPORTER";
    const IN_TRANSIT = "IN_TRANSIT";
    const DELIVERED_AT_NEIGHBOURS = "DELIVERED_AT_NEIGHBOURS";
    const DELIVERED_AT_CUSTOMER = "DELIVERED_AT_CUSTOMER";
    const PICKED_UP_AT_PICK_UP_POINT = "PICKED_UP_AT_PICK_UP_POINT";
    const AT_PICK_UP_POINT = "AT_PICK_UP_POINT";
    const RETURNED_TO_SENDER = "RETURNED_TO_SENDER";
}
