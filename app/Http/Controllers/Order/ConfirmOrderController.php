<?php

namespace App\Http\Controllers\Order;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\OrderStatus;
use Illuminate\Http\Request;

class ConfirmOrderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Order $order)
    {
        $status = $order->status;

        if ($status === OrderStatus::CONFIRMED->value) {
            return APIResponse::error('Order is already confirmed.', 400);
        }else if ($status === OrderStatus::CANCELLED->value) {
            return APIResponse::error('Cannot confirm a cancelled order.', 400);
        }

        $order->status = OrderStatus::CONFIRMED->value;
        $order->save();

        return APIResponse::success($order, 'Order confirmed successfully.');
    }
}
