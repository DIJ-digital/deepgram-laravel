## Deepgram Laravel - A Laravel Package for Deepgram AI Voice Services
This package provides a seamless integration with [Deepgram's](https://deepgram.com) AI voice services. Built following Laravel conventions.

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

## Available APIs

### Listen
Speech-to-Text transcription
```php
Deepgram::listen()->transcribeFile(absoluteFilePath: '/path/to/audio.wav', mimeType: 'audio/wav', options: [])
```

### Read  
Text summarization and intelligence
```php
Deepgram::read()->summarizeText(text: 'Your text content to summarize...', options: [])
Deepgram::read()->summarizeUrl(url: 'https://example.com/article.txt', options: [])
```

### Speak
*Coming soon - Text-to-Speech synthesis*

**Available options** for each API can be found in the [Deepgram documentation](https://developers.deepgram.com).

## Testing

Use `Deepgram::fake()` to prevent real HTTP calls during testing:

```php
use DIJ\Deepgram\Facades\Deepgram;

it('can process audio and text', function () {
    Deepgram::fake();
    
    // Both APIs return fake data - no HTTP calls made
    $transcription = Deepgram::listen()->transcribeFile(absoluteFilePath: '/path/to/audio.wav');
    $summary = Deepgram::read()->summarizeText(text: 'Long text content...');
    
    expect($transcription)->toBeArray()->toHaveKey('results');
    expect($summary)->toBeArray()->toHaveKey('results');
});
```

For more precise control, use Laravel's standard facade mocking: `Deepgram::shouldReceive()->once()->andReturn()`.

**Deepgram Laravel** was created by **[DIJ Digital](https://dij.digital)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
