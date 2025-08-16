<?php

declare(strict_types=1);

namespace DIJ\Deepgram\Api;

use DIJ\Deepgram\Exceptions\DeepgramConfigurationException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use League\Flysystem\UnableToReadFile;

final class Listen
{
    /**
     * Transcribe audio file using Deepgram /v1/listen API
     *
     * @param  array<string, mixed>  $options
     *
     * @throws DeepgramConfigurationException
     * @throws UnableToReadFile
     * @throws RequestException
     */
    public function transcribeFile(string $absoluteFilePath, string $mimeType = 'audio/wav', array $options = []): array
    {
        $apiKey = config('deepgram-laravel.api_key');
        $baseUrl = mb_rtrim((string) config('deepgram-laravel.base_url'), '/');

        if ($apiKey === null || $apiKey === '') {
            throw new DeepgramConfigurationException('Deepgram API key is not properly configured');
        }

        if ($baseUrl === '') {
            throw new DeepgramConfigurationException('Deepgram base URL is not properly configured');
        }

        if (! is_readable(filename: $absoluteFilePath)) {
            throw new UnableToReadFile('Audio file not readable: '.$absoluteFilePath);
        }

        $stream = fopen(filename: $absoluteFilePath, mode: 'rb');

        if ($stream === false) {
            throw new UnableToReadFile('Failed to open audio file for reading: '.$absoluteFilePath);
        }

        // Merge config defaults with provided options
        $transcriptionOptions = array_merge([
            'model' => config('deepgram-laravel.default_model', 'nova-2'),
            'language' => config('deepgram-laravel.default_language', 'nl'),
        ], $options);

        $queryParams = http_build_query($transcriptionOptions);

        $response = Http::withHeaders([
            'Authorization' => 'Token '.$apiKey,
            'Content-Type' => $mimeType,
        ])->withBody(stream_get_contents($stream) ?: '', $mimeType)
            ->post($baseUrl.'/listen?'.$queryParams);

        fclose($stream);

        return $response->throw()->json();
    }
}
