<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
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

    public function filter(Request $request)
    {
        $query = Product::with(['creator', 'categories', 'editors']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->oldest()->get();

        return view('inventory::products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::with('parent')->get();
        $statuses = Product::STATUSES;

        return view('inventory::products.create', compact('categories', 'statuses'));
    }

    public function modalCreate(Request $request)
    {
        $categories = Category::with('parent')->orderBy('main_code')->get();
        $statuses = Product::STATUSES;

        return view('inventory::products.partials.modal-create', [
            'categories' => $categories,
            'statuses' => $statuses,
            'prefilledCode' => $request->query('term'),
        ]);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string|min:1|max:255',
        ]);

        $products = $this->searchProducts($validated['term']);

        return response()->json([
            'found' => $products->isNotEmpty(),
            'products' => $products->map(fn (Product $product) => $this->productPayload($product))->values(),
        ]);
    }

    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'term' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        $term = trim((string) ($validated['term'] ?? $validated['code'] ?? ''));

        if ($term === '') {
            return response()->json([
                'found' => false,
                'products' => [],
            ]);
        }

        $products = $this->searchProducts($term);

        return response()->json([
            'found' => $products->isNotEmpty(),
            'products' => $products->map(fn (Product $product) => $this->productPayload($product))->values(),
        ]);
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
            $message = 'این کد محصول قبلاً برای این دسته‌بندی ثبت شده است.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => [
                        'code' => [$message],
                    ],
                ], 422);
            }

            return back()->withErrors([
                'code' => $message,
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
        $product->load('categories');

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'کالا با موفقیت تعریف و برای ثبت ورود انتخاب شد.',
                'product' => $this->productPayload($product),
            ], 201);
        }

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

    private function searchProducts(string $term): Collection
    {
        return Product::with('categories')
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', '%'.$term.'%')
                    ->orWhere('code', 'like', '%'.$term.'%')
                    ->orWhere('main_code', 'like', '%'.$term.'%')
                    ->orWhere('sku', 'like', '%'.$term.'%');
            })
            ->oldest()
            ->limit(5)
            ->get();
    }

    private function productPayload(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->code,
            'main_code' => $product->main_code,
            'unit' => $product->unit,
            'sku' => $product->sku,
            'status' => $product->status,
            'status_label' => $product->status_label,
            'price' => $product->price,
            'categories' => $product->categories->map(fn (Category $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'main_code' => $category->main_code,
            ])->values(),
        ];
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
