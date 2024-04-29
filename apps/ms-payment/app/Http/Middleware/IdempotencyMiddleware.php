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
            case TransactionAction::PAYMENT_CAPTURE:
                return $this->isPaymentCaptureDuplicate($transactionUUID);
                break;
            case TransactionAction::PAYMENT_REFUND:
                return $this->isPaymentRefundDuplicate($transactionUUID);
                break;
            default:
                return false;
                break;
        }
    }

    protected function isPaymentCaptureDuplicate($transactionUUID)
    {
        return TransactionLog::where('transaction_uuid', $transactionUUID)
            ->where('action', TransactionAction::PAYMENT_CAPTURE)
            ->where('service_identifier', 'payment')
            ->whereIn('status', ['completed', 'pending'])
            ->exists();
    }

    protected function isPaymentRefundDuplicate($transactionUUID)
    {
        return TransactionLog::where('transaction_uuid', $transactionUUID)
            ->where('action', TransactionAction::PAYMENT_REFUND)
            ->where('service_identifier', 'payment')
            ->whereIn('status', ['completed', 'pending'])
            ->exists();
    }
}
