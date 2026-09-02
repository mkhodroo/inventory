<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StockFlow\Inventory\InventoryServiceProvider;
use StockFlow\Inventory\Models\Category;
use StockFlow\Inventory\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['creator', 'categories', 'editors'])
            ->oldest()
            ->get();

        return view('inventory::products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::with('parent')->get();
        $statuses = Product::STATUSES;

        return view('inventory::products.create', compact('categories', 'statuses'));
    }

    public function show(Product $product)
    {
        $product->load(['creator', 'categories', 'editors']);

        return view('inventory::products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'category_id' => 'required|exists:'.InventoryServiceProvider::getTableName('categories').',id',
            'unit' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'status' => 'required|in:available,consumed,consignment,sold',
            'price' => 'required|numeric|min:0',
        ]);

        $category = Category::findOrFail($validated['category_id']);

        $mainCode = Product::generateMainCode($category->main_code, $validated['code']);

         $exists = Product::where('main_code', $mainCode)->exists();
        if ($exists) {
            return back()->withErrors([
                'code' => 'این کد محصول قبلاً برای این دسته‌بندی ثبت شده است.'
            ])->withInput();
        }

        $product = Product::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'main_code' => $mainCode,
            'unit' => $validated['unit'],
            'sku' => $validated['sku'],
            'status' => $validated['status'],
            'price' => $validated['price'],
            'creator_id' => $request->user()->id,
        ]);

        $product->categories()->sync([$validated['category_id']]);

        return redirect()->route('inventory.products.index')
            ->with('success', 'محصول با موفقیت ایجاد شد.');
    }

    public function edit(Product $product)
    {
        $product->load('categories');
        $categories = Category::with('parent')->get();
        $statuses = Product::STATUSES;

        return view('inventory::products.edit', compact('product', 'categories', 'statuses'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'category_id' => 'required|exists:'.InventoryServiceProvider::getTableName('categories').',id',
            'unit' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'status' => 'required|in:available,consumed,consignment,sold',
            'price' => 'required|numeric|min:0',
        ]);

        $category = Category::findOrFail($validated['category_id']);

        $newMainCode = Product::generateMainCode($category->main_code, $validated['code']);

         $exists = Product::where('main_code', $newMainCode)
            ->where('id', '!=', $product->id)
            ->exists();
            
        if ($exists) {
            return back()->withErrors([
                'code' => 'این کد محصول قبلاً برای این دسته‌بندی ثبت شده است.'
            ])->withInput();
        }


        $product->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'main_code' =>  $newMainCode,
            'unit' => $validated['unit'],
            'sku' => $validated['sku'],
            'status' => $validated['status'],
            'price' => $validated['price'],
        ]);

        $product->categories()->sync([$validated['category_id']]);
        $product->editors()->syncWithoutDetaching([$request->user()->id]);

        return redirect()->route('inventory.products.index')
            ->with('success', 'محصول با موفقیت ویرایش شد.');
    }

    public function destroy(Product $product)
    {
        $product->categories()->detach();
        $product->editors()->detach();
        $product->delete();

        return redirect()->route('inventory.products.index')
            ->with('success', 'محصول با موفقیت حذف شد.');
    }
}
