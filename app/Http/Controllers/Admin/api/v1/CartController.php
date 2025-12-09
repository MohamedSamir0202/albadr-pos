<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Item;
use App\Models\Product;
use App\Models\CartItem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    use ApiResponse;

    /**
     * Get user cart
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $cart = Cart::with('items.product')
            ->where('client_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$cart) {
            return $this->responseApi([
                'items' => [],
                'total' => 0
            ], 'Cart is empty');
        }

        $total = $cart->items->sum('total');

        return $this->responseApi([
            'items' => $cart->items,
            'total' => $total
        ]);
    }

        public function add(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $user = $request->user();

        $item = Item::findOrFail($request->item_id);

        // get or create active cart
        $cart = Cart::firstOrCreate(
            [
                'client_id' => $user->id,
                'status'  => 'active'
            ]
        );

        // check if product already exists
        $item = CartItem::where('cart_id', $cart->id)
            ->where('item_id', $item->id)
            ->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->total = $item->quantity * $item->price;
            $item->save();
        } else {
            CartItem::create([
                'cart_id'   => $cart->id,
                'item_id'=> $item->id,
                'quantity'  => $request->quantity,
                'price'     => $item->price,
                'total'     => $item->price * $request->quantity
            ]);
        }

        return $this->apiSuccessMessage('Item added to cart');
    }
/*
        public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity'     => 'required|integer|min:1'
        ]);

        $item = CartItem::findOrFail($request->cart_item_id);

        $item->quantity = $request->quantity;
        $item->total = $item->quantity * $item->price;
        $item->save();

        return $this->apiSuccessMessage('Cart item updated');
    }

    public function remove($id)
    {
        $item = CartItem::findOrFail($id);
        $item->delete();

        return $this->apiSuccessMessage('Item removed from cart');
    }

*/
}
