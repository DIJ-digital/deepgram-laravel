<?php

declare(strict_types=1);

namespace DIJ\Deepgram;

use DIJ\Deepgram\Exceptions\DeepgramConfigurationException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use League\Flysystem\UnableToReadFile;

final class Deepgram
{
    /**
     * Transcribe audio file using Deepgram API
     *
     * @throws DeepgramConfigurationException
     * @throws UnableToReadFile
     * @throws RequestException
     */
    public function transcribeFile(string $absoluteFilePath, string $mimeType = 'audio/wav'): array
    {
        $apiKey = config('deepgram.api_key');
        $baseUrl = mb_rtrim((string) config('deepgram.base_url'), '/');

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

        $queryParams = http_build_query([
            'model' => config('deepgram.default_model', 'nova-2'),
            'language' => config('deepgram.default_language', 'nl'),
            'smart_format' => (bool) config('deepgram.transcription.smart_format', true),
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Token '.$apiKey,
            'Content-Type' => $mimeType,
        ])->withBody(stream_get_contents($stream) ?: '', $mimeType)
            ->post($baseUrl.'/listen?'.$queryParams);

        fclose($stream);

        return $response->throw()->json();
    }
}
