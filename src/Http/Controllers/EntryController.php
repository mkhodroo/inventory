<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use StockFlow\Inventory\InventoryServiceProvider;
use StockFlow\Inventory\Models\Entry;
use StockFlow\Inventory\Models\EntryReason;
use StockFlow\Inventory\Models\Product;
use StockFlow\Inventory\Models\ProductWarehouse;
use StockFlow\Inventory\Models\Warehouse;

class EntryController extends Controller
{
    public function index(Request $request)
    {
        $query = Entry::with(['warehouse', 'product', 'entryReason', 'creator']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $entries = $query->oldest()->get();
        $warehouses = Warehouse::all();
        $products = Product::all();

        return view('inventory::entries.index', compact('entries', 'warehouses', 'products'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        $entryReasons = EntryReason::all();

        return view('inventory::entries.create', compact('warehouses', 'products', 'entryReasons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:'.InventoryServiceProvider::getTableName('warehouses').',id',
            'product_id' => 'required|exists:'.InventoryServiceProvider::getTableName('products').',id',
            'quantity' => 'required|integer|min:1',
            'entry_reason_id' => 'required|exists:'.InventoryServiceProvider::getTableName('entry_reasons').',id',
        ]);

        $validated['creator_id'] = $request->user()->id;

        DB::transaction(function () use ($validated) {
            Entry::create($validated);

            ProductWarehouse::updateOrCreate(
                [
                    'warehouse_id' => $validated['warehouse_id'],
                    'product_id' => $validated['product_id'],
                ],
                [
                    'quantity' => DB::raw('quantity + '.$validated['quantity']),
                ]
            );
        });

        return redirect()->route('inventory.entries.index')
            ->with('success', 'ورود کالا با موفقیت ثبت شد.');
    }

    public function edit(Entry $entry)
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        $entryReasons = EntryReason::all();

        return view('inventory::entries.edit', compact('entry', 'warehouses', 'products', 'entryReasons'));
    }

    public function update(Request $request, Entry $entry)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:'.InventoryServiceProvider::getTableName('warehouses').',id',
            'product_id' => 'required|exists:'.InventoryServiceProvider::getTableName('products').',id',
            'quantity' => 'required|integer|min:1',
            'entry_reason_id' => 'required|exists:'.InventoryServiceProvider::getTableName('entry_reasons').',id',
        ]);

        DB::transaction(function () use ($entry, $validated) {
            ProductWarehouse::updateOrCreate(
                [
                    'warehouse_id' => $entry->warehouse_id,
                    'product_id' => $entry->product_id,
                ],
                [
                    'quantity' => DB::raw('quantity - '.$entry->quantity),
                ]
            );

            $entry->update($validated);

            ProductWarehouse::updateOrCreate(
                [
                    'warehouse_id' => $validated['warehouse_id'],
                    'product_id' => $validated['product_id'],
                ],
                [
                    'quantity' => DB::raw('quantity + '.$validated['quantity']),
                ]
            );
        });

        return redirect()->route('inventory.entries.index')
            ->with('success', 'ورود کالا با موفقیت ویرایش شد.');
    }

    public function destroy(Entry $entry)
    {
        DB::transaction(function () use ($entry) {
            ProductWarehouse::where('warehouse_id', $entry->warehouse_id)
                ->where('product_id', $entry->product_id)
                ->update(['quantity' => DB::raw('quantity - '.$entry->quantity)]);

            $entry->delete();
        });

        return redirect()->route('inventory.entries.index')
            ->with('success', 'ورود کالا با موفقیت حذف شد.');
    }
}
