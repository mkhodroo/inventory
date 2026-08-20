<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use StockFlow\Inventory\InventoryServiceProvider;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(InventoryServiceProvider::getTableName('products'), function (Blueprint $table) {
            $table->string('status')->default('available')->after('sku');
            $table->decimal('price', 15, 2)->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table(InventoryServiceProvider::getTableName('products'), function (Blueprint $table) {
            $table->dropColumn(['status', 'price']);
        });
    }
};
