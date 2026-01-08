<?php

namespace App\Http\Controllers\Payment;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class ReadPaymentController extends Controller
{

    public function __construct(
        protected PaymentService $paymentService
    ) {}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Payment $payment)
    {
        $payment = $this->paymentService->find($payment->id);

        return APIResponse::success($payment);
    }
}
