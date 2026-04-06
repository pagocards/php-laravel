<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pagocards Configuration
    |--------------------------------------------------------------------------
    |
    | These configuration values are used to connect to the Pagocards API.
    | You can get your credentials at https://pagocards.com/settings/developer
    |
    */

    'public_key' => env('PAGOCARDS_PUBLIC_KEY'),

    'secret_key' => env('PAGOCARDS_SECRET_KEY'),

    'base_url' => env('PAGOCARDS_API_URL', 'https://pagocards.com'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout for API requests in seconds
    |
    */
    'timeout' => 30,

    /*
    |--------------------------------------------------------------------------
    | Enable Debugging
    |--------------------------------------------------------------------------
    |
    | Set to true to enable detailed logging for API calls
    |
    */
    'debug' => env('PAGOCARDS_DEBUG', false),
];

