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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->foreignId('category_id')->constrained(
                table: 'product_categories', indexName: 'products_category_id_foreign'
            );
            $table->integer('price');
            $table->tinyInteger('status')->default(0)->comment('0: 正常, 1: 缺貨, 2: 下架');
            $table->string('description', 1000)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->string('created_by', 20)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('updated_by', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
