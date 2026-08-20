<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StockFlow\Inventory\Models\SettlementReason;

class SettlementReasonController extends Controller
{
    public function index()
    {
        $settlementReasons = SettlementReason::with('creator')->oldest()->get();

        return view('inventory::settlement-reasons.index', compact('settlementReasons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['creator_id'] = $request->user()->id;

        SettlementReason::create($validated);

        return redirect()->route('inventory.settlement-reasons.index')
            ->with('success', 'دلیل تسویه با موفقیت ایجاد شد.');
    }

    public function update(Request $request, SettlementReason $settlementReason)
    {
        if ($settlementReason->settlements()->exists()) {
            return back()->with('error', 'امکان ویرایش این دلیل وجود ندارد زیرا در تسویه استفاده شده است.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $settlementReason->update($validated);

        return redirect()->route('inventory.settlement-reasons.index')
            ->with('success', 'دلیل تسویه با موفقیت ویرایش شد.');
    }

    public function destroy(SettlementReason $settlementReason)
    {
        if ($settlementReason->settlements()->exists()) {
            return back()->with('error', 'امکان حذف این دلیل وجود ندارد زیرا در تسویه استفاده شده است.');
        }

        $settlementReason->delete();

        return redirect()->route('inventory.settlement-reasons.index')
            ->with('success', 'دلیل تسویه با موفقیت حذف شد.');
    }
}
