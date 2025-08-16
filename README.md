## Deepgram Laravel - A Laravel Package for Deepgram AI Voice Services
This package provides a seamless integration with [Deepgram's](https://deepgram.com) AI voice services. Built following Laravel conventions.

### This package supports the following features:

#### Speech-to-Text
- Transcribe local audio files
- Configurable transcription options (model, language, smart formatting)

> **Requires [PHP 8.3](https://php.net/releases/) or higher and Laravel 11+**

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
DEEPGRAM_DEFAULT_LANGUAGE=nl
```

### How to use this package

#### Basic File Transcription
```php
use DIJ\Deepgram\Facades\Deepgram;

// Basic transcription with config defaults
$result = Deepgram::transcribeFile('/path/to/audio.wav', 'audio/wav');

// With custom options per call
$result = Deepgram::transcribeFile('/path/to/audio.wav', 'audio/wav', [
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

**Deepgram Laravel** was created by **[DIJ Digital](https://dij.digital)** under the **[MIT license](https://opensource.org/licenses/MIT)**.
