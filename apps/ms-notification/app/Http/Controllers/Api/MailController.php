<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Send mail notification
     */
    public function mail(Request $request)
    {
        $order = (object) [
            'id' => 1,
            'total_price' => 1000,
            'created_at' => '2024-03-10',
            'status' => 'completed',
        ];

        Mail::to("customer@example.com")->send(new OrderCompleted($order));
    }
}
