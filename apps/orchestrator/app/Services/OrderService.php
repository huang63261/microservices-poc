<?php

namespace App\Services;

use App\Services\Http\AbstractHttpRequest;
use Illuminate\Support\Facades\Http;

class OrderService extends AbstractHttpRequest
{
    public function __construct() {
        $this->http = Http::order();
    }

    /**
     * Get all orders
     *
     * @param array $params
     */
    public function getOrders(array $params = [])
    {
        $response = $this->send('GET', '/orders', [
            'query' => $params
        ]);

        $order = $response['data'];

        return $order;
    }
    /**
     * Get order by id
     *
     * @param string $orderId
     * @return mixed
     */
    public function getOrderById(string $orderId)
    {
        $response = $this->send('GET', "/orders/{$orderId}");

        $order = $response['data'];

        return $order;
    }

    /**
     * Create order
     *
     * @param array $data
     */
    public function createOrder(array $data)
    {
        $response = $this->send('POST', '/orders', [
            'json' => $data
        ]);

        $order = $response['data'];

        return $order;
    }

    /**
     * Update order
     *
     * @param string $orderId
     * @param array $data
     */
    public function updateOrder(string $orderId, array $data)
    {
        $response = $this->send('PUT', "/orders/{$orderId}", [
            'json' => $data
        ]);

        $order = $response['data'];

        return $order;
    }

    /**
     * Delete order
     *
     * @param string $orderId
     */
    public function deleteOrder(string $orderId)
    {
        $response = $this->send('DELETE', "/orders/{$orderId}");

        return $response;
    }
}