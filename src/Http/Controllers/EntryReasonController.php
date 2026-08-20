<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StockFlow\Inventory\Models\EntryReason;

class EntryReasonController extends Controller
{
    public function index()
    {
        $entryReasons = EntryReason::with('creator')->oldest()->get();

        return view('inventory::entry-reasons.index', compact('entryReasons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['creator_id'] = $request->user()->id;

        EntryReason::create($validated);

        return redirect()->route('inventory.entry-reasons.index')
            ->with('success', 'دلیل ورود با موفقیت ایجاد شد.');
    }

    public function update(Request $request, EntryReason $entryReason)
    {
        if ($entryReason->entries()->exists()) {
            return back()->with('error', 'امکان ویرایش این دلیل وجود ندارد زیرا در ورود کالا استفاده شده است.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $entryReason->update($validated);

        return redirect()->route('inventory.entry-reasons.index')
            ->with('success', 'دلیل ورود با موفقیت ویرایش شد.');
    }

    public function destroy(EntryReason $entryReason)
    {
        if ($entryReason->entries()->exists()) {
            return back()->with('error', 'امکان حذف این دلیل وجود ندارد زیرا در ورود کالا استفاده شده است.');
        }

        $entryReason->delete();

        return redirect()->route('inventory.entry-reasons.index')
            ->with('success', 'دلیل ورود با موفقیت حذف شد.');
    }
}
