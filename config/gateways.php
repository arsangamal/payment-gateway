<?php

use App\Gateways\FawryGateway;
use App\Gateways\StripGateway;

return [

    'default' => 'fawry',

    'gateways' => [
        'stripe' => [
            'implementation' => StripGateway::class,
            'api_key' => env('STRIPE_API_KEY', ''),
            'api_secret' => env('STRIPE_API_SECRET', ''),
        ],
        'fawry' => [
            'implementation' => FawryGateway::class,
            'client_id' => env('FAWRY_CLIENT_ID', ''),
            'client_secret' => env('FAWRY_CLIENT_SECRET', ''),
        ],
    ],
];
