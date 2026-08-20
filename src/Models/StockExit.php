<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use StockFlow\Inventory\InventoryServiceProvider;

class StockExit extends Model
{
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
        'receiver_id',
        'exit_reason_id',
        'creator_id',
    ];

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('exits');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Receiver::class, 'receiver_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function exitReason(): BelongsTo
    {
        return $this->belongsTo(ExitReason::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'creator_id');
    }
}
