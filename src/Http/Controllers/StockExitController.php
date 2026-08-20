<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use StockFlow\Inventory\InventoryServiceProvider;
use StockFlow\Inventory\Models\ExitReason;
use StockFlow\Inventory\Models\Product;
use StockFlow\Inventory\Models\ProductWarehouse;
use StockFlow\Inventory\Models\Receiver;
use StockFlow\Inventory\Models\StockExit;
use StockFlow\Inventory\Models\Warehouse;

class StockExitController extends Controller
{
    public function index(Request $request)
    {
        $query = StockExit::with(['warehouse', 'product', 'receiver', 'exitReason', 'creator']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('receiver_id')) {
            $query->where('receiver_id', $request->receiver_id);
        }

        $exits = $query->oldest()->get();
        $warehouses = Warehouse::all();
        $products = Product::all();
        $receivers = Receiver::withTrashed()->get();

        return view('inventory::exits.index', compact('exits', 'warehouses', 'products', 'receivers'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        $exitReasons = ExitReason::all();
        $receivers = Receiver::active()->get();

        return view('inventory::exits.create', compact('warehouses', 'products', 'exitReasons', 'receivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:'.InventoryServiceProvider::getTableName('warehouses').',id',
            'product_id' => 'required|exists:'.InventoryServiceProvider::getTableName('products').',id',
            'quantity' => 'required|integer|min:1',
            'receiver_id' => 'required|exists:'.InventoryServiceProvider::getTableName('receivers').',id',
            'exit_reason_id' => 'required|exists:'.InventoryServiceProvider::getTableName('exit_reasons').',id',
        ]);

        $stock = ProductWarehouse::where('warehouse_id', $validated['warehouse_id'])
            ->where('product_id', $validated['product_id'])
            ->first();

        if (! $stock || $stock->quantity < $validated['quantity']) {
            return back()->withInput()->with('error', 'موجودی کافی نیست.');
        }

        $validated['creator_id'] = $request->user()->id;

        DB::transaction(function () use ($validated) {
            StockExit::create($validated);

            ProductWarehouse::where('warehouse_id', $validated['warehouse_id'])
                ->where('product_id', $validated['product_id'])
                ->decrement('quantity', $validated['quantity']);
        });

        return redirect()->route('inventory.exits.index')
            ->with('success', 'خروج کالا با موفقیت ثبت شد.');
    }

    public function edit(StockExit $exit)
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        $exitReasons = ExitReason::all();
        $receivers = Receiver::active()->get();

        return view('inventory::exits.edit', compact('exit', 'warehouses', 'products', 'exitReasons', 'receivers'));
    }

    public function update(Request $request, StockExit $exit)
    {
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:'.InventoryServiceProvider::getTableName('warehouses').',id',
            'product_id' => 'required|exists:'.InventoryServiceProvider::getTableName('products').',id',
            'quantity' => 'required|integer|min:1',
            'receiver_id' => 'required|exists:'.InventoryServiceProvider::getTableName('receivers').',id',
            'exit_reason_id' => 'required|exists:'.InventoryServiceProvider::getTableName('exit_reasons').',id',
        ]);

        $stock = ProductWarehouse::where('warehouse_id', $validated['warehouse_id'])
            ->where('product_id', $validated['product_id'])
            ->first();

        $available = $stock->quantity ?? 0;

        if ($exit->warehouse_id === $validated['warehouse_id'] && $exit->product_id === $validated['product_id']) {
            $available += $exit->quantity;
        }

        if ($available < $validated['quantity']) {
            return back()->withInput()->with('error', 'موجودی کافی نیست.');
        }

        DB::transaction(function () use ($exit, $validated) {
            ProductWarehouse::where('warehouse_id', $exit->warehouse_id)
                ->where('product_id', $exit->product_id)
                ->increment('quantity', $exit->quantity);

            $exit->update($validated);

            ProductWarehouse::where('warehouse_id', $validated['warehouse_id'])
                ->where('product_id', $validated['product_id'])
                ->decrement('quantity', $validated['quantity']);
        });

        return redirect()->route('inventory.exits.index')
            ->with('success', 'خروج کالا با موفقیت ویرایش شد.');
    }

    public function destroy(StockExit $exit)
    {
        DB::transaction(function () use ($exit) {
            ProductWarehouse::where('warehouse_id', $exit->warehouse_id)
                ->where('product_id', $exit->product_id)
                ->increment('quantity', $exit->quantity);

            $exit->delete();
        });

        return redirect()->route('inventory.exits.index')
            ->with('success', 'خروج کالا با موفقیت حذف شد.');
    }
}
