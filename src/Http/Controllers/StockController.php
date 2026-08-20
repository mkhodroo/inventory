<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StockFlow\Inventory\Models\Entry;
use StockFlow\Inventory\Models\Product;
use StockFlow\Inventory\Models\ProductWarehouse;
use StockFlow\Inventory\Models\StockExit;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductWarehouse::with(['product', 'warehouse'])
            ->selectRaw('product_id, sum(quantity) as total_quantity')
            ->groupBy('product_id')
            ->having('total_quantity', '>', 0);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $stocks = $query->get();

        $productIds = $stocks->pluck('product_id')->toArray();

        $warehouseStocks = ProductWarehouse::with(['product', 'warehouse'])
            ->whereIn('product_id', $productIds)
            ->where('quantity', '>', 0)
            ->get()
            ->groupBy('product_id');

        $products = Product::all();

        return view('inventory::stock.index', compact('stocks', 'warehouseStocks', 'products'));
    }

    public function show(Product $product)
    {
        $warehouseStocks = ProductWarehouse::where('product_id', $product->id)
            ->where('quantity', '>', 0)
            ->with('warehouse')
            ->get();

        $entries = Entry::where('product_id', $product->id)
            ->with(['warehouse', 'entryReason', 'creator'])
            ->oldest()
            ->get();

        $exits = StockExit::where('product_id', $product->id)
            ->with(['warehouse', 'exitReason', 'creator'])
            ->oldest()
            ->get();

        return view('inventory::stock.show', compact('product', 'warehouseStocks', 'entries', 'exits'));
    }
}
