<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use StockFlow\Inventory\InventoryServiceProvider;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(InventoryServiceProvider::getTableName('category_editors'), function (Blueprint $table) {
            $table->foreignId('category_id')->constrained(InventoryServiceProvider::getTableName('categories'))->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(InventoryServiceProvider::getTableName('category_editors'));
    }
};
