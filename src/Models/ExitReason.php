<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use StockFlow\Inventory\InventoryServiceProvider;

class ExitReason extends Model
{
    protected $fillable = [
        'name',
        'creator_id',
    ];

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('exit_reasons');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'creator_id');
    }

    public function exits(): HasMany
    {
        return $this->hasMany(StockExit::class);
    }
}
