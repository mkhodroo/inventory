<?php

namespace StockFlow\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use StockFlow\Inventory\InventoryServiceProvider;

class ProductSpecification extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'value',
    ];

    public function getTable(): string
    {
        return InventoryServiceProvider::getTableName('product_specifications');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
