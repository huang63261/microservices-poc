<?php

namespace App\Services;

use App\Mail\OrderCompleted;
use Illuminate\Support\Facades\Mail;

class MailingService
{
    private const STATUS_MAP = [
        '0' => 'Order pending',
        '1' => 'Order processing',
        '2' => 'Order completed',
        '3' => 'Order failed',
    ];

    public static function sendMail(array $order)
    {
        $order = (object) $order;
        $order->status = self::STATUS_MAP[$order->status];

        Mail::to("customer@example.com")->send(new OrderCompleted($order));
    }
}