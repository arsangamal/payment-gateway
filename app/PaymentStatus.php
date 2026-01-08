<?php

namespace App;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case SUCCESSFUL = 'successful';
    case FAILED = 'failed';


    public function default(): string
    {
        return self::PENDING->value;
    }
}
