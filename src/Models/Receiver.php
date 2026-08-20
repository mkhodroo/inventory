<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use StockFlow\Inventory\InventoryServiceProvider;

class Receiver extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'is_active',
        'creator_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('receivers');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'creator_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'user_id');
    }

    public function exits(): HasMany
    {
        return $this->hasMany(StockExit::class, 'receiver_id');
    }

    public function settlements(): HasMany
    {
        return $this->hasMany(Settlement::class);
    }

    public function deliveredQuantity(int $productId): int
    {
        return $this->exits()->where('product_id', $productId)->sum('quantity');
    }

    public function settledQuantity(int $productId): int
    {
        return $this->settlements()->where('product_id', $productId)->sum('quantity');
    }

    public function remainingQuantity(int $productId): int
    {
        return $this->deliveredQuantity($productId) - $this->settledQuantity($productId);
    }
}
