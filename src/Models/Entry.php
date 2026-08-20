<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use StockFlow\Inventory\InventoryServiceProvider;

class Entry extends Model
{
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
        'entry_reason_id',
        'creator_id',
    ];

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('entries');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function entryReason(): BelongsTo
    {
        return $this->belongsTo(EntryReason::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'creator_id');
    }
}
