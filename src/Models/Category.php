<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use StockFlow\Inventory\InventoryServiceProvider;

class Category extends Model
{
    protected $fillable = [
        'name',
        'code',
        'main_code',
        'parent_id',
        'creator_id',
    ];

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('categories');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'creator_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function editors(): BelongsToMany
    {
        return $this->belongsToMany(config('inventory.user_model', 'App\Models\User'), InventoryServiceProvider::getTableName('category_editors'));
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, InventoryServiceProvider::getTableName('category_product'));
    }

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public static function generateMainCode(?int $parentId, string $code): string
    {
        if ($parentId === null) {
            return $code;
        }

        $parent = static::findOrFail($parentId);

        return $parent->main_code.$code;
    }
}
