<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Item;

class CartService
{
    public function getActiveCart(int $clientId): Cart
    {
        return Cart::firstOrCreate([
            'client_id' => $clientId,
            'status'    => 'active',
        ]);
    }

    public function addItem(int $clientId, int $itemId, int $quantity): void
    {
        $item = Item::findOrFail($itemId);
        $cart = $this->getActiveCart($clientId);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('item_id', $item->id)
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $quantity,
                'total'    => ($cartItem->quantity + $quantity) * $cartItem->price,
            ]);
        } else {
            CartItem::create([
                'cart_id'  => $cart->id,
                'item_id'  => $item->id,
                'quantity' => $quantity,
                'price'    => $item->price,
                'total'    => $item->price * $quantity,
            ]);
        }
    }

    public function updateItem(int $cartItemId, int $quantity): void
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        $cartItem->update([
            'quantity' => $quantity,
            'total'    => $quantity * $cartItem->price,
        ]);
    }
}
