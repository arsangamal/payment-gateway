<?php

namespace App\Gateways;

use App\Interfaces\IPaymentGateway;

class PaymentGatewayFactory
{
    /**
     * Create a new class instance.
     */
    public static function make(string $gateway = ''): IPaymentGateway
    {
        $gateway = $gateway ?: config('gateways.default');
        $gatewayConfig = config("gateways.gateways.$gateway");

        if (!$gatewayConfig) {
            throw new \InvalidArgumentException("Payment gateway [$gateway] is not supported.");
        }

        $implementation = $gatewayConfig['implementation'];

        return new $implementation();
    }
}
