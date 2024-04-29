<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InventoryCheckController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {
        $this->middleware('idempotency')->only('checkAvailability');
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $result = $this->inventoryService->checkInventory($request->input('items'));

        return response()->json($result, Response::HTTP_OK);
    }
}
