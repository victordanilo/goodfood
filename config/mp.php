<?php

return [
    'public_key' => env('MP_PUBLIC_KEY', ''),
    'access_token' => env('MP_ACCESS_TOKEN', ''),
    'redirect_url' => env('MP_REDIRECT_URL', ''),
    'application_fee' => env('MP_APPLICATION_FEE', '10'),
    'money_release_days' => env('MP_MONEY_RELEASE_DAYS', '30'),
    'statement_descriptor' => env('MP_STATMENT_DESCRIPTOR', 'GoodFood'),
];
