<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use StockFlow\Inventory\InventoryServiceProvider;

return new class extends Migration
{
    public function up(): void
    {
        $exits = InventoryServiceProvider::getTableName('exits');
        $receivers = InventoryServiceProvider::getTableName('receivers');

        DB::table($exits)->select('receiver')->distinct()->orderBy('receiver')->get()->each(function ($row) use ($exits, $receivers) {
            if ($row->receiver === null || DB::table($receivers)->where('name', $row->receiver)->exists()) {
                return;
            }

            $firstExit = DB::table($exits)->where('receiver', $row->receiver)->first();

            DB::table($receivers)->insert([
                'name' => $row->receiver,
                'is_active' => true,
                'creator_id' => $firstExit->creator_id ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Schema::table($exits, function (Blueprint $table) {
            $table->foreignId('receiver_id')->nullable()->after('product_id');
        });

        DB::table($exits)->get()->each(function ($exit) use ($exits, $receivers) {
            $receiver = DB::table($receivers)->where('name', $exit->receiver)->first();

            if ($receiver) {
                DB::table($exits)->where('id', $exit->id)->update(['receiver_id' => $receiver->id]);
            }
        });

        Schema::table($exits, function (Blueprint $table) {
            $table->dropColumn('receiver');
            $table->foreign('receiver_id')->references('id')->on(InventoryServiceProvider::getTableName('receivers'));
        });
    }

    public function down(): void
    {
        $exits = InventoryServiceProvider::getTableName('exits');
        $receivers = InventoryServiceProvider::getTableName('receivers');

        Schema::table($exits, function (Blueprint $table) {
            $table->dropForeign(['receiver_id']);
            $table->dropColumn('receiver_id');
            $table->string('receiver')->nullable();
        });

        DB::table($exits)->get()->each(function ($exit) use ($exits, $receivers) {
            $receiver = DB::table($receivers)->find($exit->receiver_id);

            if ($receiver) {
                DB::table($exits)->where('id', $exit->id)->update(['receiver' => $receiver->name]);
            }
        });
    }
};
