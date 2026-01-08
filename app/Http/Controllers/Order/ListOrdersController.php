<?php

namespace App\Http\Controllers\Order;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class ListOrdersController extends Controller
{

    public function __construct(
        protected OrderService $orderService
    )
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $orders = $this->orderService->all();

        return APIResponse::success($orders, 'Orders retrieved successfully.');
    }
}
