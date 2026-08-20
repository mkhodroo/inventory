<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use StockFlow\Inventory\InventoryServiceProvider;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(InventoryServiceProvider::getTableName('product_warehouses'), function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained(InventoryServiceProvider::getTableName('warehouses'));
            $table->foreignId('product_id')->constrained(InventoryServiceProvider::getTableName('products'));
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['warehouse_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(InventoryServiceProvider::getTableName('product_warehouses'));
    }
};
