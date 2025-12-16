<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Enums\OrderStatusEnum;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiResponse;

    public function checkout(Request $request)
    {
        $request->validate([
            'shipping_name'    => 'required|string|max:100',
            'shipping_address' => 'required|string|max:500',
            'shipping_phone'   => 'required|string|max:20',
        ]);

        $client = $request->user();

        $cart = Cart::with('items.item')
            ->where('client_id', $client->id)
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return $this->apiErrorMessage('Cart is empty', 422);
        }

        DB::beginTransaction();

        try {
            $itemsTotal = $cart->items->sum('total');
            $shippingCost = 50;
            $totalPrice = $itemsTotal + $shippingCost;

            $order = Order::create([
                'client_id'      => $client->id,
                'status'         => OrderStatusEnum::CONFIRMED->value,
                'payment_method' => 1,
                'price'          => $itemsTotal,
                'shipping_cost'  => $shippingCost,
                'total_price'    => $totalPrice,
            ]);

            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id'    => $order->id,
                    'item_id'     => $cartItem->item_id,
                    'unit_price'  => $cartItem->price,
                    'quantity'    => $cartItem->quantity,
                    'total_price' => $cartItem->total,
                ]);
            }

            $cart->update(['status' => 'completed']);

            DB::commit();

            return $this->responseApi($order->load('items'), 'Order placed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiErrorMessage('Checkout failed', 500);
        }
    }

    public function index(Request $request)
    {
        $orders = Order::with('items.item')
            ->where('client_id', $request->user()->id)
            ->latest()
            ->get();

        return $this->responseApi($orders);
    }

    public function show(Order $order)
    {
        if ($order->client_id !== auth('api')->id()) {
            return $this->apiErrorMessage('Unauthorized', 403);
        }

        return $this->responseApi($order->load('items.item'));
    }
}
