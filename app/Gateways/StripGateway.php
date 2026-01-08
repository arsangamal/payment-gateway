<?php

namespace App\Gateways;

use App\Interfaces\IPaymentGateway;
use App\Models\Order;

class StripGateway implements IPaymentGateway
{
    public function pay(Order $order): bool
    {
        sleep(2); // simulate network delay

        return true;
    }
}
