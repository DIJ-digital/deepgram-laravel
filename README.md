## Deepgram Laravel - A Laravel Package for Deepgram AI Voice Services
This package provides a seamless integration with [Deepgram's](https://deepgram.com) AI voice services. Built following Laravel conventions.

### This package supports the following features:

#### Speech-to-Text
- Transcribe local audio files
- Configurable transcription options (model, language, smart formatting)

> **Requires [PHP 8.3](https://php.net/releases/) or higher and Laravel 12+**

⚡️ Install the package using **Composer**:
```bash  
composer require dij-digital/deepgram-laravel  
```  

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag="deepgram-laravel-config"
```

Add your Deepgram API credentials to your `.env` file:

```env
DEEPGRAM_API_KEY=your-api-key-here
DEEPGRAM_BASE_URL=https://api.deepgram.com/v1
DEEPGRAM_DEFAULT_MODEL=nova-2
DEEPGRAM_DEFAULT_LANGUAGE=en-US
```

### How to use this package

#### Basic File Transcription
```php
use DIJ\Deepgram\Facades\Deepgram;

// Basic transcription with config defaults
$result = Deepgram::listen()->transcribeFile('/path/to/audio.wav', 'audio/wav');

// With custom options per call
$result = Deepgram::listen()->transcribeFile('/path/to/audio.wav', 'audio/wav', [
    'model' => 'nova-3',
    'language' => 'en',
    'smart_format' => true,
    'punctuate' => true,
    'diarize' => true,
]);
```

## Configuration Options

The published config file (`config/deepgram-laravel.php`) contains the following options:

```php
return [
    'api_key' => env('DEEPGRAM_API_KEY'),
    'base_url' => env('DEEPGRAM_BASE_URL', 'https://api.deepgram.com/v1'),
    'default_model' => env('DEEPGRAM_DEFAULT_MODEL', 'nova-2'),
    'default_language' => env('DEEPGRAM_DEFAULT_LANGUAGE', 'nl'),

];
```

## Testing

This package includes a simple fake implementation for testing purposes, so you can test your application without making real API calls to Deepgram.

### Basic Usage

```php
use DIJ\Deepgram\Facades\Deepgram;

it('can process audio files', function () {
    // Arrange
    $fake = Deepgram::fake();
    
    // Act - Use Deepgram normally in your application code
    Deepgram::listen()->transcribeFile('/path/to/audio.wav');
    
    // Assert - Check what was called
    $calls = $fake->listen()->getTranscriptions();
    expect($calls)->toHaveCount(1);
});
```

### Using Mockery

You can also use Laravel's built-in Mockery support for precise mocking:

```php
it('can mock specific responses', function () {
    // Mock the response instead of using fake
    Deepgram::shouldReceive('listen->transcribeFile')
        ->once()
        ->with('/test.wav', 'audio/wav', ['model' => 'nova-2'])
        ->andReturn([
            'metadata' => ['duration' => 5.0],
            'results' => [
                'channels' => [
                    [
                        'alternatives' => [
                            ['transcript' => 'Custom mocked result', 'confidence' => 1.0],
                        ],
                    ],
                ],
            ],
        ]);

    // Your application code gets the mocked response
    $result = Deepgram::listen()->transcribeFile('/test.wav', 'audio/wav', ['model' => 'nova-2']);
    
    expect($result['results']['channels'][0]['alternatives'][0]['transcript'])
        ->toBe('Custom mocked result');
});
```

**Deepgram Laravel** was created by **[DIJ Digital](https://dij.digital)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
