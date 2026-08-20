<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StockFlow\Inventory\Models\Receiver;

class ReceiverController extends Controller
{
    public function index()
    {
        $receivers = Receiver::with(['user', 'creator'])
            ->withCount('exits')
            ->oldest()
            ->get();

        return view('inventory::receivers.index', compact('receivers'));
    }

    public function create()
    {
        $users = config('inventory.user_model')::orderBy('name')->get();

        return view('inventory::receivers.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $validated['creator_id'] = $request->user()->id;

        Receiver::create($validated);

        return redirect()->route('inventory.receivers.index')
            ->with('success', 'تحویل گیرنده با موفقیت ایجاد شد.');
    }

    public function show(Receiver $receiver)
    {
        $receiver->load(['user', 'creator']);

        $exits = $receiver->exits()->with(['warehouse', 'product', 'exitReason', 'creator'])->oldest()->get();

        $settlements = $receiver->settlements()->with(['product', 'settlementReason', 'creator'])->oldest()->get();

        $balances = [];

        foreach ($exits->groupBy('product_id') as $productId => $productExits) {
            $product = $productExits->first()->product;
            $delivered = $receiver->deliveredQuantity($productId);
            $settled = $receiver->settledQuantity($productId);

            $balances[] = [
                'product' => $product,
                'delivered' => $delivered,
                'settled' => $settled,
                'remaining' => $delivered - $settled,
            ];
        }

        return view('inventory::receivers.show', compact('receiver', 'exits', 'settlements', 'balances'));
    }

    public function edit(Receiver $receiver)
    {
        $users = config('inventory.user_model')::orderBy('name')->get();

        return view('inventory::receivers.edit', compact('receiver', 'users'));
    }

    public function update(Request $request, Receiver $receiver)
    {
        $validated = $this->validateData($request);

        $receiver->update($validated);

        return redirect()->route('inventory.receivers.index')
            ->with('success', 'تحویل گیرنده با موفقیت ویرایش شد.');
    }

    public function destroy(Receiver $receiver)
    {
        if ($receiver->exits()->exists()) {
            return back()->with('error', 'امکان حذف این تحویل گیرنده وجود ندارد زیرا کالایی به او تحویل شده است.');
        }

        $receiver->delete();

        return redirect()->route('inventory.receivers.index')
            ->with('success', 'تحویل گیرنده با موفقیت حذف شد.');
    }

    public function toggleActive(Receiver $receiver)
    {
        $receiver->update(['is_active' => ! $receiver->is_active]);

        return back()->with('success', $receiver->is_active ? 'تحویل گیرنده فعال شد.' : 'تحویل گیرنده غیرفعال شد.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'is_active' => 'required|boolean',
        ]);
    }
}
