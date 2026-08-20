<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StockFlow\Inventory\Models\ProductWarehouse;
use StockFlow\Inventory\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with(['creator', 'editors'])
            ->withCount(['entries', 'exits'])
            ->oldest()
            ->get();

        return view('inventory::warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('inventory::warehouses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:255',
        ]);

        $validated['creator_id'] = $request->user()->id;

        Warehouse::create($validated);

        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'انبار با موفقیت ایجاد شد.');
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load(['creator', 'editors']);

        return view('inventory::warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        return view('inventory::warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:255',
        ]);

        $warehouse->update($validated);
        $warehouse->editors()->syncWithoutDetaching([$request->user()->id]);

        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'انبار با موفقیت ویرایش شد.');
    }

    public function destroy(Warehouse $warehouse)
    {
        if ($warehouse->entries()->exists() || $warehouse->exits()->exists()) {
            return back()->with('error', 'امکان حذف انبار وجود ندارد زیرا ورود یا خروج کالا در آن ثبت شده است.');
        }

        ProductWarehouse::where('warehouse_id', $warehouse->id)->delete();
        $warehouse->editors()->detach();
        $warehouse->delete();

        return redirect()->route('inventory.warehouses.index')
            ->with('success', 'انبار با موفقیت حذف شد.');
    }
}
