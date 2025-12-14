<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class OrderController extends Controller
{
    use ApiResponse;
        public function checkout(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'shipping_phone'   => 'required|string|max:20',
        ]);

        $user = $request->user();

        $cart = Cart::with('items.product')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return $this->apiErrorMessage('Cart is empty');
        }

        // Create Order
        $order = Order::create([
            'user_id'          => $user->id,
            'total'            => $cart->items->sum('total'),
            'status'           => 'pending',
            'payment_method'   => 'cash_on_delivery',
            'shipping_name'    => $request->shipping_name,
            'shipping_address' => $request->shipping_address,
            'shipping_phone'   => $request->shipping_phone,
        ]);

        // Create Order Items (Snapshot)
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'item_id' => $item->item_id,
                'item_name'=> $item->item->name,
                'price'      => $item->price,
                'quantity'   => $item->quantity,
                'total'      => $item->total,
            ]);
        }

        // Close cart
        $cart->update(['status' => 'converted']);

        return $this->responseApi($order, 'Order created successfully');
    }

    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items')
            ->latest()
            ->get();

        return $this->responseApi($orders);
    }

    public function show(Order $order, Request $request)
    {
        if ($order->client_id !== $request->user()->id) {
            return $this->apiErrorMessage('Unauthorized', 403);
        }

        return $this->responseApi(
            $order->load('items'),
            'Order details'
        );
    }
}
