<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2>訂單完成！</h2>
                </div>
                <div class="card-body">
                    <p>感謝您的訂購，此訂單已成功付款。以下是訂單資訊：</p>
                    <p>訂單編號: {{ $order->id }}</p>
                    <p>總金額: ${{ $order->total_price }}</p>
                    <p>訂單日期: {{ $order->created_at }}</p>
                    <p>訂單狀態: {{ $order->status}}</p>
                </div>
            </div>
        </div>
    </div>
</div>