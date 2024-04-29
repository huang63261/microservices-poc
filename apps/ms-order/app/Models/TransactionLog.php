<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $table = 'transaction_log';

    protected $fillable = [
        'transaction_uuid',
        'service_identifier',
        'action',
        'order_id',
        'status',
    ];
}
