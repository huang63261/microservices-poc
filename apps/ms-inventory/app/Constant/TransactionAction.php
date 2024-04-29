<?php

namespace App\Constant;

class TransactionAction
{
    const ORDER_CREATE = 'order.create';
    const ORDER_APPROVE = 'order.approve';
    const ORDER_CANCEL = 'order.cancel';
    const INVENTORY_CHECK = 'inventory.check';
    const INVENTORY_LOCK = 'inventory.lock';
    const INVENTORY_UNLOCK = 'inventory.unlock';
    const INVENTORY_DEDUCT = 'inventory.deduct';
    const PAYMENT_CAPTURE = 'payment.capture';
    const PAYMENT_REFUND = 'payment.refund';
}