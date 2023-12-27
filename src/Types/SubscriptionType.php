<?php

namespace Budgetlens\BolRetailerApi\Types;

enum SubscriptionType: string
{
    const PROCESS_STATUS = "PROCESS_STATUS";
    const SHIPMENT = "SHIPMENT";
    const OFFER = "OFFER";
}
