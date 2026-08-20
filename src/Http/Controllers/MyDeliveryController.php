<?php

namespace StockFlow\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use StockFlow\Inventory\Models\Receiver;

class MyDeliveryController extends Controller
{
    public function index()
    {
        $receivers = Receiver::where('user_id', auth()->id())->get();

        return view('inventory::my-deliveries.index', compact('receivers'));
    }

    public function show(Receiver $receiver)
    {
        if ($receiver->user_id !== auth()->id()) {
            abort(403);
        }

        $receiver->load('user');

        $exits = $receiver->exits()->with(['warehouse', 'product', 'exitReason'])->oldest()->get();

        $settlements = $receiver->settlements()->with(['product', 'settlementReason'])->oldest()->get();

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

        return view('inventory::my-deliveries.show', compact('receiver', 'exits', 'settlements', 'balances'));
    }
}
