<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use StockFlow\Inventory\InventoryServiceProvider;

class Settlement extends Model
{
    protected $fillable = [
        'receiver_id',
        'product_id',
        'quantity',
        'settlement_reason_id',
        'document',
        'creator_id',
    ];

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('settlements');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Receiver::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function settlementReason(): BelongsTo
    {
        return $this->belongsTo(SettlementReason::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'creator_id');
    }
}
