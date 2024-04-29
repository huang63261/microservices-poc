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
            case TransactionAction::ORDER_CREATE:
                return $this->isOrderCreateDuplicate($transactionUUID);
                break;
            case TransactionAction::ORDER_APPROVE:
                return $this->isOrderApproveDuplicate($transactionUUID);
                break;
            default:
                return false;
                break;
        }
    }

    protected function isOrderCreateDuplicate($transactionUUID)
    {
        return TransactionLog::where('transaction_uuid', $transactionUUID)
            ->where('action', TransactionAction::ORDER_CREATE)
            ->where('service_identifier', 'order')
            ->whereIn('status', ['completed', 'pending'])
            ->exists();
    }

    protected function isOrderApproveDuplicate($transactionUUID)
    {
        return TransactionLog::where('transaction_uuid', $transactionUUID)
            ->where('action', TransactionAction::ORDER_APPROVE)
            ->where('service_identifier', 'order')
            ->whereIn('status', ['completed', 'pending'])
            ->exists();
    }
}
