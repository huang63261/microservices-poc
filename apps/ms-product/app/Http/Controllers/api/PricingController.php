<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PricingService;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function __construct(
        protected PricingService $pricingService
    ) {}

    public function pricing(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer'
        ]);

        $items = $request->input('items');

        $price = $this->pricingService->calculate($items);

        return response()->json($price);
    }
}
