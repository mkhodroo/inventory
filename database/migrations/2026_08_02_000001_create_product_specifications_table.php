<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use StockFlow\Inventory\InventoryServiceProvider;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(InventoryServiceProvider::getTableName('product_specifications'), function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained(InventoryServiceProvider::getTableName('products'))
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('value')->nullable();
            $table->timestamps();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(InventoryServiceProvider::getTableName('product_specifications'));
    }
};
