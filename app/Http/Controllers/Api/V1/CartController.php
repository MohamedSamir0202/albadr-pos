<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddToCartRequest;
use App\Services\CartService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiResponse;

    public function __construct(private CartService $cartService)
    {
    }

    public function index(Request $request)
    {
        $client = $request->user();

        $cart = $this->cartService
            ->getActiveCart($client->id)
            ->load('items.item');

        return $this->responseApi([
            'items' => $cart->items,
            'total' => $cart->items->sum('total'),
        ]);
    }

    public function add(AddToCartRequest $request)
    {
        $this->cartService->addItem(
            $request->user()->id,
            $request->item_id,
            $request->quantity
        );

        return $this->apiSuccessMessage('Item added to cart successfully');
    }

    public function update(AddToCartRequest $request)
    {
        $this->cartService->updateItem(
            $request->cart_item_id,
            $request->quantity
        );

        return $this->apiSuccessMessage('Cart item updated successfully');
    }
}
