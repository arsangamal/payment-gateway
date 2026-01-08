<?php

namespace App\Http\Controllers\Order;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use App\OrderStatus;
use App\Services\OrderService;
use Illuminate\Support\Arr;

class CreateOrderController extends Controller
{

    public function __construct(
        protected OrderService $orderService
    ){
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateOrderRequest $request)
    {
        $data = $request->validated();

        $order = $this->orderService->createOrder($data);

        return APIResponse::success($order, 'Order created successfully', 201);
    }
}
