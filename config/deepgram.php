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
    'default_language' => env('DEEPGRAM_DEFAULT_LANGUAGE', 'nl'),

    /*
    |--------------------------------------------------------------------------
    | Speech-to-Text Default Options
    |--------------------------------------------------------------------------
    */
    'transcription' => [
        'smart_format' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */
    'max_file_size' => env('DEEPGRAM_MAX_FILE_SIZE', 150 * 1024 * 1024), // 150MB

    'supported_formats' => [
        'mp3', 'mp4', 'wav', 'flac', 'aac', 'ogg', 'webm', 'm4a',
    ],
];
