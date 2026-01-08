<?php

namespace App\Http\Controllers\Payment;

use App\APIResponse;
use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class ListPaymentsController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    )
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $payments = $this->paymentService->all();

        return APIResponse::success($payments, 'Payments retrieved successfully');
    }
}
