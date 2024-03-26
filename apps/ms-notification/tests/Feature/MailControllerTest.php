<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\MailController;
use App\Mail\OrderCompleted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /**
     * Test mail notification with request parameter.
     *
     * @return void
     */
    public function test_mail_notification_with_request_parameter()
    {
        Mail::fake();

        $order = (object) [
            'id' => 2,
            'order_date' => '2024-03-15',
            'total' => 1500,
            'shipping_address' => '456 Elm St, Los Angeles, CA 90001',
            'payment_method' => 'PayPal',
        ];

        $this->postJson(route('mail'), ['order' => $order]);

        Mail::assertSent(OrderCompleted::class, function (OrderCompleted $mail) use ($order) {
            return $mail->getOrder()->id === $order->id;
        });
    }
}