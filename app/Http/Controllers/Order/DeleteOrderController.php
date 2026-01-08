<?php

namespace App\Http\Controllers\Order;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\PaymentStatus;
use Illuminate\Http\Request;

class DeleteOrderController extends Controller
{
    public function __construct(
        protected \App\Services\OrderService $orderService
    )
    {
    }

    public function __invoke(Order $order)
    {
        if ($order->payment->status === PaymentStatus::SUCCESSFUL->value) {
            return APIResponse::error('Cannot delete an order with successful payment.', 400);
        }

        $orderId = $order->id;

        $this->orderService->delete($orderId);

        return APIResponse::success([], 'Order deleted successfully', 200);
    }
}
