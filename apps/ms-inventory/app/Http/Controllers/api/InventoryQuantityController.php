<?php

namespace App\Http\Controllers\Api;

use App\Constant\TransactionAction;
use App\Http\Controllers\Controller;
use App\Models\TransactionLog;
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
        $transactionLog = TransactionLog::create([
            'transaction_uuid' => $request->header('transaction-uuid'),
            'service_identifier' => 'inventory',
            'order_id' => null,
            'action' => TransactionAction::INVENTORY_LOCK,
            'status' => 'pending',
            'detail' => json_encode($request->all()),
        ]);

        try {
            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $result = $this->inventoryService->lockInventory($request->input('items'));
        } catch (\Exception $e) {
            $transactionLog->update(['status' => 'failed', 'detail' => $e->getMessage()]);

            return response()->json(['message' => $e->getMessage()], 400);
        }

        $transactionLog->update(['status' => 'completed']);

        return response()->json($result);
    }

    public function unlock(Request $request)
    {
        $transactionLog = TransactionLog::create([
            'transaction_uuid' => $request->header('transaction-uuid'),
            'service_identifier' => 'inventory',
            'order_id' => null,
            'action' => TransactionAction::INVENTORY_UNLOCK,
            'status' => 'pending',
            'detail' => json_encode($request->all()),
        ]);

        try {
            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $result = $this->inventoryService->unlockInventory($request->input('items'));
        } catch (\Exception $e) {
            $transactionLog->update(['status' => 'failed', 'detail' => $e->getMessage()]);

            return response()->json(['message' => $e->getMessage()], 400);
        }

        $transactionLog->update(['status' => 'completed']);

        return response()->json($result);
    }

    public function deduct(Request $request)
    {
        $transactionLog = TransactionLog::create([
            'transaction_uuid' => $request->header('transaction-uuid'),
            'service_identifier' => 'inventory',
            'order_id' => null,
            'action' => TransactionAction::INVENTORY_DEDUCT,
            'status' => 'pending',
            'detail' => json_encode($request->all()),
        ]);

        try {
            $request->validate([
                'items' => 'required|array',
                'items.*.product_id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $result = $this->inventoryService->deductInventory($request->input('items'));
        } catch (\Exception $e) {
            $transactionLog->update(['status' => 'failed', 'detail' => $e->getMessage()]);

            return response()->json(['message' => $e->getMessage()], 400);
        }

        $transactionLog->update(['status' => 'completed']);

        return response()->json($result);
    }
}
