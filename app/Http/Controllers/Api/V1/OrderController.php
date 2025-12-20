<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CheckoutRequest;
use App\Services\OrderService;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function checkout(CheckoutRequest $request)
    {
        try {
            $order = $this->orderService->createOrder(
                $request->user(),
                $request->validated()
            );

            return $this->responseApi($order, 'Order placed successfully');
        } catch (\Exception $e) {
            return $this->apiErrorMessage($e->getMessage(), $e->getCode() ?: 500);
        }
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->getClientOrders($request->user()->id);
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
