<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use StockFlow\Inventory\Models\Entry;
use StockFlow\Inventory\Models\Product;
use StockFlow\Inventory\Models\StockExit;
use StockFlow\Inventory\Models\Warehouse;

class MovementController extends Controller
{
    public function index(Request $request)
    {
        $entriesQuery = Entry::with(['warehouse', 'product', 'creator', 'entryReason']);

        $exitsQuery = StockExit::with(['warehouse', 'product', 'receiver', 'creator', 'exitReason']);

        if ($request->filled('warehouse_id')) {
            $entriesQuery->where('warehouse_id', $request->warehouse_id);
            $exitsQuery->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('product_id')) {
            $entriesQuery->where('product_id', $request->product_id);
            $exitsQuery->where('product_id', $request->product_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $entriesQuery->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('warehouse', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
            $exitsQuery->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('warehouse', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $type = $request->input('type');

        $entries = collect();
        $exits = collect();

        if ($type !== 'exit') {
            $entries = $entriesQuery->get()->map(function ($entry) {
                $entry->type = 'entry';
                $entry->reason_name = $entry->entryReason->name ?? '-';
                $entry->receiver = null;

                return $entry;
            });
        }

        if ($type !== 'entry') {
            $exits = $exitsQuery->get()->map(function ($exit) {
                $exit->type = 'exit';
                $exit->reason_name = $exit->exitReason->name ?? '-';
                $exit->receiver_name = $exit->receiver->name ?? '-';

                return $exit;
            });
        }

        $movements = $entries->concat($exits)
            ->sortBy('created_at')
            ->values();

        $currentPage = $request->input('page', 1);
        $perPage = 20;
        $paginated = new LengthAwarePaginator(
            $movements->slice(($currentPage - 1) * $perPage, $perPage),
            $movements->count(),
            $perPage,
            $currentPage,
            ['query' => $request->query()]
        );

        $warehouses = Warehouse::all();
        $products = Product::all();

        return view('inventory::movements.index', compact('movements', 'paginated', 'warehouses', 'products'));
    }
}
