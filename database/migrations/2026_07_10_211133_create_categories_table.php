<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use StockFlow\Inventory\InventoryServiceProvider;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(InventoryServiceProvider::getTableName('categories'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('main_code');
            $table->foreignId('parent_id')->nullable()->constrained(InventoryServiceProvider::getTableName('categories'))->nullOnDelete();
            $table->foreignId('creator_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(InventoryServiceProvider::getTableName('categories'));
    }
};
