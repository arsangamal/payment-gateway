<?php

namespace App\Interfaces;

use App\Models\Order;

interface IPaymentGateway
{
    public function pay(Order $order): bool;
}
