<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use StockFlow\Inventory\InventoryServiceProvider;
use StockFlow\Inventory\Models\Product;
use StockFlow\Inventory\Models\Receiver;
use StockFlow\Inventory\Models\Settlement;
use StockFlow\Inventory\Models\SettlementReason;
use StockFlow\Inventory\Models\StockExit;

class SettlementController extends Controller
{
    public function index(Request $request)
    {
        $query = Settlement::with(['receiver', 'product', 'settlementReason', 'creator']);

        if ($request->filled('receiver_id')) {
            $query->where('receiver_id', $request->receiver_id);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $settlements = $query->oldest()->get();
        $receivers = Receiver::withTrashed()->get();
        $products = Product::all();

        return view('inventory::settlements.index', compact('settlements', 'receivers', 'products'));
    }

    public function create()
    {
        $receivers = Receiver::active()->get();
        $products = Product::all();
        $settlementReasons = SettlementReason::all();

        return view('inventory::settlements.create', compact('receivers', 'products', 'settlementReasons'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($validated['quantity'] > $this->availableQuantity($validated['receiver_id'], $validated['product_id'])) {
            return back()->withInput()->with('error', 'مقدار تسویه از کالای تحویل شده بیشتر است.');
        }

        if ($request->hasFile('document')) {
            $validated['document'] = $request->file('document')->store('settlements', 'public');
        }

        $validated['creator_id'] = $request->user()->id;

        Settlement::create($validated);

        return redirect()->route('inventory.settlements.index')
            ->with('success', 'تسویه با موفقیت ثبت شد.');
    }

    public function edit(Settlement $settlement)
    {
        $receivers = Receiver::active()->get();
        $products = Product::all();
        $settlementReasons = SettlementReason::all();

        return view('inventory::settlements.edit', compact('settlement', 'receivers', 'products', 'settlementReasons'));
    }

    public function update(Request $request, Settlement $settlement)
    {
        $validated = $this->validateData($request);

        $available = $this->availableQuantity($validated['receiver_id'], $validated['product_id'], $settlement->id);

        if ($validated['quantity'] > $available) {
            return back()->withInput()->with('error', 'مقدار تسویه از کالای تحویل شده بیشتر است.');
        }

        if ($request->hasFile('document')) {
            if ($settlement->document) {
                Storage::disk('public')->delete($settlement->document);
            }

            $validated['document'] = $request->file('document')->store('settlements', 'public');
        }

        $settlement->update($validated);

        return redirect()->route('inventory.settlements.index')
            ->with('success', 'تسویه با موفقیت ویرایش شد.');
    }

    public function destroy(Settlement $settlement)
    {
        if ($settlement->document) {
            Storage::disk('public')->delete($settlement->document);
        }

        $settlement->delete();

        return redirect()->route('inventory.settlements.index')
            ->with('success', 'تسویه با موفقیت حذف شد.');
    }

    public function downloadDocument(Settlement $settlement)
    {
        if (! $settlement->document || ! Storage::disk('public')->exists($settlement->document)) {
            abort(404);
        }

        return Storage::disk('public')->download($settlement->document);
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'receiver_id' => 'required|exists:'.InventoryServiceProvider::getTableName('receivers').',id',
            'product_id' => 'required|exists:'.InventoryServiceProvider::getTableName('products').',id',
            'quantity' => 'required|integer|min:1',
            'settlement_reason_id' => 'required|exists:'.InventoryServiceProvider::getTableName('settlement_reasons').',id',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
    }

    protected function availableQuantity(int $receiverId, int $productId, ?int $exceptId = null): int
    {
        $delivered = StockExit::where('receiver_id', $receiverId)
            ->where('product_id', $productId)
            ->sum('quantity');

        $settled = Settlement::where('receiver_id', $receiverId)
            ->where('product_id', $productId)
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
            ->sum('quantity');

        return max(0, $delivered - $settled);
    }
}
