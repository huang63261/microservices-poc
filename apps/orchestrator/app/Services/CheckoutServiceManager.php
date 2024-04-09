<?php

namespace App\Services;

class CheckoutServiceManager
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected OrderService $orderService,
        protected ProductService $productService,
        protected PaymentService $paymentService
    ) {}

    /**
     * Checkout the products from shopping cart.
     *
     * @param array<array><string,int> $items
     * @throws \Exception
     */
    public function checkout(array $items)
    {
        try {
            $productsIds = array_column($items, 'product_id');

            $checkResult = $this->inventoryService->checkProductInventory($items);

            if ($checkResult['is_available'] === 0) {
                throw new \Exception(
                    json_encode([
                        'code' => '400',
                        'message' => $checkResult['detail']
                    ]), 200);
            }

            // Lock the inventory
            RabbitMQService::sendInventoryLockingEvent($items);

            $products = $this->productService->getProductsByIds($productsIds);
            $products = collect($products)->keyBy('id')->toArray();

            foreach ($items as $key => $item) {
                $product = $products[$item['product_id']];
                $items[$key]['product_name'] = $product['name'];
                $items[$key]['price'] = $product['price'];
            }

            // Get the total price of the items
            // todo : What if any price of the product changes after this step?
            $totalPrice = $this->productService->getPricing($items);

            // Create the order
            $orderData = [
                'customer_id' => '1',
                'total_price' => $totalPrice,
                'items' => $items,
            ];
            $order = $this->orderService->createOrder($orderData);

            return $order;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Process the payment for the order.
     *
     * @param int $orderId
     * @param string $paymentMethod
     * @throws \Exception
     */
    public function payment($orderId, $paymentMethod)
    {
        try {
            $order = $this->orderService->getOrderById($orderId);

            if ($order['status'] === 2) {
                throw new \Exception('Order is already completed');
            }

            $paymentData = [
                'order_id' => $orderId,
                'amount' => $order['total_price'],
                'payment_method' => $paymentMethod,
            ];

            $payment = $this->paymentService->processPayment($paymentData);

            // Change the order status to 'completed'
            if ($payment['code'] === 200) {
                // todo What if the order update fails?
                $order = $this->orderService->updateOrder($order['id'], ['status' => '2']);
                RabbitMQService::sendOrderCompletedEvent($order);
            }

            return $payment;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
