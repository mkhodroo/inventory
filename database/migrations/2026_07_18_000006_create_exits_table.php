<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use StockFlow\Inventory\InventoryServiceProvider;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(InventoryServiceProvider::getTableName('exits'), function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained(InventoryServiceProvider::getTableName('warehouses'));
            $table->foreignId('product_id')->constrained(InventoryServiceProvider::getTableName('products'));
            $table->integer('quantity');
            $table->string('receiver');
            $table->foreignId('exit_reason_id')->constrained(InventoryServiceProvider::getTableName('exit_reasons'));
            $table->foreignId('creator_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(InventoryServiceProvider::getTableName('exits'));
    }
};
