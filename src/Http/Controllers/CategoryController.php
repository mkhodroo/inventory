<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StockFlow\Inventory\InventoryServiceProvider;
use StockFlow\Inventory\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['creator', 'parent', 'editors'])
            ->withCount('products')
            ->oldest()
            ->get();

        return view('inventory::categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::with('parent')->get();

        return view('inventory::categories.create', compact('parentCategories'));
    }

    public function show(Category $category)
    {
        $category->load(['creator', 'parent', 'editors', 'products']);

        return view('inventory::categories.show', compact('category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:'.InventoryServiceProvider::getTableName('categories').',id',
        ]);

        $validated['main_code'] = Category::generateMainCode(
            $validated['parent_id'],
            $validated['code']
        );
        $validated['creator_id'] = $request->user()->id;

        $category = Category::create($validated);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'دسته‌بندی با موفقیت ایجاد شد.');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::with('parent')
            ->where('id', '!=', $category->id)
            ->get();

        return view('inventory::categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:'.InventoryServiceProvider::getTableName('categories').',id',
        ]);

        $validated['main_code'] = Category::generateMainCode(
            $validated['parent_id'],
            $validated['code']
        );

        $category->update($validated);

        $category->editors()->syncWithoutDetaching([$request->user()->id]);

        return redirect()->route('inventory.categories.index')
            ->with('success', 'دسته‌بندی با موفقیت ویرایش شد.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'امکان حذف دسته‌بندی وجود ندارد زیرا محصولاتی در آن وجود دارد.');
        }

        $category->editors()->detach();
        $category->delete();

        return redirect()->route('inventory.categories.index')
            ->with('success', 'دسته‌بندی با موفقیت حذف شد.');
    }
}
