<?php

namespace App\Services;

use App\Services\Http\AbstractHttpRequest;
use Illuminate\Support\Facades\Http;

class PaymentService extends AbstractHttpRequest
{
    public function __construct() {
        $this->http = Http::payment();
    }

    public function processPayment(array $data)
    {
        $response = $this->send('POST', '/payment', [
            'json' => $data
        ]);

        return $response;
    }
}