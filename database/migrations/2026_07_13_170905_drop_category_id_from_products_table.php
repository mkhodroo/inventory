<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use StockFlow\Inventory\InventoryServiceProvider;

return new class extends Migration
{
    public function up(): void
    {
        $table = InventoryServiceProvider::getTableName('products');

        if (Schema::hasColumn($table, 'category_id')) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }
    }

    public function down(): void
    {
        $table = InventoryServiceProvider::getTableName('products');

        if (! Schema::hasColumn($table, 'category_id')) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('category_id')->constrained(InventoryServiceProvider::getTableName('categories'));
            });
        }
    }
};
