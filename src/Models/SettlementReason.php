<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use StockFlow\Inventory\InventoryServiceProvider;

class SettlementReason extends Model
{
    protected $fillable = [
        'name',
        'creator_id',
    ];

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('settlement_reasons');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'creator_id');
    }

    public function settlements(): HasMany
    {
        return $this->hasMany(Settlement::class);
    }
}
