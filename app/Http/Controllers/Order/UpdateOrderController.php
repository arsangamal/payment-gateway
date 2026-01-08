<?php

namespace App\Http\Controllers\Order;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class UpdateOrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    )
    {
    }


    public function __invoke(UpdateOrderRequest $request, Order $order)
    {
        $data = $request->validated();

        $order = $this->orderService->update($order->id, $data);

        return APIResponse::success($order, message: 'Order updated successfully');

    }
}
