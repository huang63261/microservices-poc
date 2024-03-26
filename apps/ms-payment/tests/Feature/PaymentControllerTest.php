<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the payment endpoint with valid data.
     */
    public function test_payment_success(): void
    {
        $data = [
            'order_id' => 1,
            'amount' => 1050,
            'payment_method' => 'credit_card',
        ];

        $response = $this->post(route('payment'), $data, ['Accept' => 'application/json']);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Payment success',
            ]);
    }

    /**
     * Test the payment endpoint with missing required data.
     */
    public function test_payment_validation_error(): void
    {
        $data = [
            // Missing 'order_id'
            'amount' => 1050,
            'payment_method' => 'credit_card',
        ];

        $this->post(route('payment'), $data, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['order_id']);
    }
}