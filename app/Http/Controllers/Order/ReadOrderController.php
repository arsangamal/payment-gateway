<?php

namespace App\Http\Controllers\Order;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class ReadOrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    )
    {
    }

    public function __invoke(Request $request, Order $order)
    {
        $order = $this->orderService->find($order->id);

        return APIResponse::success($order);
    }
}
