<?php

namespace App\Http\Controllers\Payment;

use App\APIResponse;
use App\Gateways\PaymentGatewayFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PayRequest;
use App\Models\Order;
use App\OrderStatus;
use App\PaymentStatus;
use Illuminate\Http\Request;

class PayController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PayRequest $request, Order $order)
    {
        if ($order->status != OrderStatus::CONFIRMED->value) {
            return APIResponse::error('Only confirmed orders can be paid', 400);
        }

        if ($order->payment->status == PaymentStatus::SUCCESSFUL->value) {
            return APIResponse::error('Order is already paid', 400);
        }

        $gateway = PaymentGatewayFactory::make($request->input('gateway'));

        $isPayed = $gateway->pay($order);

        $order->payment->payment_gateway = $request->input('gateway');

        if ($isPayed) {
            // assume that the external payment is is retreived
            $order->payment->external_payment_id = uniqid('pay_', true);
            $order->payment->status = PaymentStatus::SUCCESSFUL->value;
            $order->payment->save();
            return APIResponse::success($order->payment, 'Payment successful');
        } else {
            $order->payment->status = PaymentStatus::FAILED->value;
            $order->payment->save();
            return APIResponse::error('Payment failed', 400);
        }
    }
}
