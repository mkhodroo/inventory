<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StockFlow\Inventory\Models\ExitReason;

class ExitReasonController extends Controller
{
    public function index()
    {
        $exitReasons = ExitReason::with('creator')->oldest()->get();

        return view('inventory::exit-reasons.index', compact('exitReasons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['creator_id'] = $request->user()->id;

        ExitReason::create($validated);

        return redirect()->route('inventory.exit-reasons.index')
            ->with('success', 'دلیل خروج با موفقیت ایجاد شد.');
    }

    public function update(Request $request, ExitReason $exitReason)
    {
        if ($exitReason->exits()->exists()) {
            return back()->with('error', 'امکان ویرایش این دلیل وجود ندارد زیرا در خروج کالا استفاده شده است.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $exitReason->update($validated);

        return redirect()->route('inventory.exit-reasons.index')
            ->with('success', 'دلیل خروج با موفقیت ویرایش شد.');
    }

    public function destroy(ExitReason $exitReason)
    {
        if ($exitReason->exits()->exists()) {
            return back()->with('error', 'امکان حذف این دلیل وجود ندارد زیرا در خروج کالا استفاده شده است.');
        }

        $exitReason->delete();

        return redirect()->route('inventory.exit-reasons.index')
            ->with('success', 'دلیل خروج با موفقیت حذف شد.');
    }
}
