<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Enums\OrderStatusEnum;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder($client, array $data)
    {
        $cart = Cart::with('items.item')
            ->where('client_id', $client->id)
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception('Cart is empty', 422);
        }

        return DB::transaction(function () use ($client, $cart, $data) {
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
                'shipping_name'  => $data['shipping_name'],
                'shipping_address' => $data['shipping_address'],
                'shipping_phone'   => $data['shipping_phone'],
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

            return $order->load('items.item');
        });
    }

    public function getClientOrders($clientId)
    {
        return Order::with('items.item')
            ->where('client_id', $clientId)
            ->latest()
            ->get();
    }
}
