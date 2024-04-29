<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryQuantityController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {
        $this->middleware('idempotency')->only('lock', 'unlock', 'deduct');
    }

    /**
     * Lock the inventory.
     */
    public function lock(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $result = $this->inventoryService->lockInventory($request->input('items'));

        return response()->json($result);
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $result = $this->inventoryService->unlockInventory($request->input('items'));

        return response()->json($result);
    }

    public function deduct(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $result = $this->inventoryService->deductInventory($request->input('items'));

        return response()->json($result);
    }
}
