<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    public $incrementing = false;

    public $keyType = 'string';

    protected $primaryKey = 'transaction_uuid';

    protected $table = 'transaction_log';

    protected $fillable = [
        'transaction_uuid',
        'service_identifier',
        'action',
        'status',
    ];
}
