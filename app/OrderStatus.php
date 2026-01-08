<?php

namespace App;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELED = 'canceled';

    public static function default(): self
    {
        return self::PENDING;
    }
}
