<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2>Order Completed</h2>
                </div>
                <div class="card-body">
                    <p>Thank you for your order! Your order has been successfully completed.</p>
                    <p>Order ID: {{ $order->id }}</p>
                    <p>Order Total: ${{ $order->total }}</p>
                    <p>Shipping Address: {{ $order->shipping_address }}</p>
                    <p>Payment Method: {{ $order->payment_method }}</p>
                </div>
            </div>
        </div>
    </div>
</div>