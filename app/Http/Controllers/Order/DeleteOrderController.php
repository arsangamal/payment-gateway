<?php

namespace App\Http\Controllers\Order;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DeleteOrderController extends Controller
{
    public function __construct(
        protected \App\Services\OrderService $orderService
    )
    {
    }

    public function __invoke(Request $request, Order $order)
    {
        $orderId = $order->id;

        $this->orderService->delete($orderId);

        return APIResponse::success(['message' => 'Order deleted successfully.'], 200);
    }
}
