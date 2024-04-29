<?php

namespace App\Http\Middleware;

use App\Constant\TransactionAction;
use App\Models\TransactionLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdempotencyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $transactionUUID = $request->header('Transaction-Uuid');
        $action = $request->header('Transaction-Action');

        if (empty($transactionUUID)) {
            return response()->json(['message' => 'header transaction-uuid is required'], 400);
        }

        if (empty($action)) {
            return response()->json(['message' => 'header action is required'], 400);
        }

        if ($this->isDuplicate($transactionUUID, $action)) {
            return response()->json(['message' => 'Duplicate request'], 409);
        }

        return $next($request);
    }

    protected function isDuplicate($transactionUUID, $action)
    {
        switch ($action) {
            case TransactionAction::INVENTORY_LOCK:
                return $this->isInventoryLockDuplicate($transactionUUID);
                break;
            case TransactionAction::INVENTORY_UNLOCK:
                return $this->isInventoryUnlockDuplicate($transactionUUID);
                break;
            case TransactionAction::INVENTORY_DEDUCT:
                return $this->isInventoryDeductDuplicate($transactionUUID);
                break;
            default:
                return false;
                break;
        }
    }

    protected function isInventoryLockDuplicate($transactionUUID)
    {
        return TransactionLog::where('transaction_uuid', $transactionUUID)
            ->where('action', TransactionAction::INVENTORY_LOCK)
            ->whereIn('status', ['completed', 'pending'])
            ->where('service_identifier', 'inventory')
            ->exists();
    }

    protected function isInventoryUnlockDuplicate($transactionUUID)
    {
        return TransactionLog::where('transaction_uuid', $transactionUUID)
            ->where('action', TransactionAction::INVENTORY_UNLOCK)
            ->whereIn('status', ['completed', 'pending'])
            ->where('service_identifier', 'inventory')
            ->exists();
    }

    protected function isInventoryDeductDuplicate($transactionUUID)
    {
        return TransactionLog::where('transaction_uuid', $transactionUUID)
            ->where('action', TransactionAction::INVENTORY_DEDUCT)
            ->whereIn('status', ['completed', 'pending'])
            ->where('service_identifier', 'inventory')
            ->exists();
    }
}
