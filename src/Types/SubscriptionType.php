<?php

namespace Budgetlens\BolRetailerApi\Types;

enum SubscriptionType: string
{
    case WEBHOOK = "WEBHOOK";
    case GCP_PUBSUB = "GCP_PUBSUB";
}
