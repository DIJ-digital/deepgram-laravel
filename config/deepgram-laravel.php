<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Deepgram API Configuration
    |--------------------------------------------------------------------------
    */
    'api_key' => env('DEEPGRAM_API_KEY'),
    'base_url' => env('DEEPGRAM_BASE_URL', 'https://api.deepgram.com/v1'),

    /*
    |--------------------------------------------------------------------------
    | Default Model Settings
    |--------------------------------------------------------------------------
    */
    'default_model' => env('DEEPGRAM_DEFAULT_MODEL', 'nova-2'),
    'default_language' => env('DEEPGRAM_DEFAULT_LANGUAGE', 'en-US'),
];
