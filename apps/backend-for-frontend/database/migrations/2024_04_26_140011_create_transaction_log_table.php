<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_log', function (Blueprint $table) {
            $table->id('id');
            $table->string('transaction_uuid');
            $table->string('service_identifier');
            $table->string('order_id')->nullable();
            $table->enum('action', ['order.create', 'order.approve', 'inventory.lock', 'inventory.unlock', 'inventory.deduct', 'payment.capture', 'payment.refund']);
            $table->enum('status', ['pending', 'completed', 'failed']);
            $table->string('detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_log');
    }
};
