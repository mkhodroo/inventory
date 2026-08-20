<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use StockFlow\Inventory\InventoryServiceProvider;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(InventoryServiceProvider::getTableName('settlements'), function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiver_id')->constrained(InventoryServiceProvider::getTableName('receivers'));
            $table->foreignId('product_id')->constrained(InventoryServiceProvider::getTableName('products'));
            $table->integer('quantity');
            $table->foreignId('settlement_reason_id')->constrained(InventoryServiceProvider::getTableName('settlement_reasons'));
            $table->string('document')->nullable();
            $table->foreignId('creator_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(InventoryServiceProvider::getTableName('settlements'));
    }
};
