<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use StockFlow\Inventory\InventoryServiceProvider;

class Product extends Model
{
    protected $fillable = [
        'name',
        'code',
        'main_code',
        'unit',
        'sku',
        'status',
        'price',
        'creator_id',
    ];

    public const STATUS_AVAILABLE = 'available';

    public const STATUS_CONSUMED = 'consumed';

    public const STATUS_CONSIGNMENT = 'consignment';

    public const STATUS_SOLD = 'sold';

    public const STATUSES = [
        self::STATUS_AVAILABLE => 'موجود',
        self::STATUS_CONSUMED => 'مصرف شده',
        self::STATUS_CONSIGNMENT => 'امانی',
        self::STATUS_SOLD => 'فروش رفته',
    ];

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('products');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(config('inventory.user_model', 'App\Models\User'), 'creator_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, InventoryServiceProvider::getTableName('category_product'));
    }

    public function editors(): BelongsToMany
    {
        return $this->belongsToMany(config('inventory.user_model', 'App\Models\User'), InventoryServiceProvider::getTableName('product_editors'));
    }

    public static function generateMainCode(string $categoryMainCode, string $code): string
    {
        return $categoryMainCode.$code;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
