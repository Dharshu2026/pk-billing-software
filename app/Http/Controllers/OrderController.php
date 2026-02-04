<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request) {
    // 1. Order create panroam
    $order = Order::create([
        'shop_name' => $request->shop_name,
        'status' => 'pending'
    ]);

    // 2. Items-ai store panroam
    foreach ($request->items as $item) {
        if (!empty($item['name']) && !empty($item['qty'])) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['name'],
                'quantity' => $item['qty']
            ]);
        }
    }

    return redirect()->back()->with('success', 'Order Placed Successfully! PK Agency will review it.');
}
}
