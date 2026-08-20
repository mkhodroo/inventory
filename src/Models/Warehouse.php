<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use StockFlow\Inventory\InventoryServiceProvider;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'creator_id',
    ];

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('warehouses');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'creator_id');
    }

    public function editors(): BelongsToMany
    {
        return $this->belongsToMany(config('inventory.user_model', 'App\Models\User'), InventoryServiceProvider::getTableName('warehouse_editors'));
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    public function exits(): HasMany
    {
        return $this->hasMany(StockExit::class);
    }
}
