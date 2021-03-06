<?php
namespace Budgetlens\BolRetailerApi\Types;

class EventTypes
{
    const CONFIRM_SHIPMENT = "CONFIRM_SHIPMENT";
    const CANCEL_ORDER = "CANCEL_ORDER";
    const CHANGE_TRANSPORT = "CHANGE_TRANSPORT";
    const HANDLE_RETURN_ITEM = "HANDLE_RETURN_ITEM";
    const CREATE_RETURN_ITEM = "CREATE_RETURN_ITEM";
    const CREATE_INBOUND = "CREATE_INBOUND";
    const DELETE_OFFER = "DELETE_OFFER";
    const CREATE_OFFER = "CREATE_OFFER";
    const UPDATE_OFFER = "UPDATE_OFFER";
    const UPDATE_OFFER_STOCK = "UPDATE_OFFER_STOCK";
    const UPDATE_OFFER_PRICE = "UPDATE_OFFER_PRICE";
    const CREATE_OFFER_EXPORT = "CREATE_OFFER_EXPORT";
    const UNPUBLISHED_OFFER_REPORT = "UNPUBLISHED_OFFER_REPORT";
    const CREATE_PRODUCT_CONTENT = "CREATE_PRODUCT_CONTENT";
    const CREATE_SUBSCRIPTION = "CREATE_SUBSCRIPTION";
    const UPDATE_SUBSCRIPTION = "UPDATE_SUBSCRIPTION";
    const DELETE_SUBSCRIPTION = "DELETE_SUBSCRIPTION";
    const SEND_SUBSCRIPTION_TST_MSG = "SEND_SUBSCRIPTION_TST_MSG";
    const CREATE_SHIPPING_LABEL = "CREATE_SHIPPING_LABEL";
}
