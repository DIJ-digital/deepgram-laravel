<?php

declare(strict_types=1);

namespace DIJ\Deepgram\Api;

use DIJ\Deepgram\Exceptions\DeepgramConfigurationException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Read
{
    /**
     * Summarize text using Deepgram /v1/read API
     *
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     *
     * @throws DeepgramConfigurationException
     * @throws RequestException
     */
    public function summarizeText(string $text, array $options = []): array
    {
        return $this->summarize(['text' => $text], $options);
    }

    /**
     * Summarize text from URL using Deepgram /v1/read API
     *
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     *
     * @throws DeepgramConfigurationException
     * @throws RequestException
     */
    public function summarizeUrl(string $url, array $options = []): array
    {
        return $this->summarize(['url' => $url], $options);
    }

    /* -------------------- Helper methods -------------------- */

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    private function summarize(array $payload, array $options = []): array
    {
        $apiKey = config('deepgram-laravel.api_key');
        $baseUrl = mb_rtrim((string) config('deepgram-laravel.base_url'), '/');

        if ($apiKey === null || $apiKey === '') {
            throw new DeepgramConfigurationException('Deepgram API key is not properly configured');
        }

        if ($baseUrl === '') {
            throw new DeepgramConfigurationException('Deepgram base URL is not properly configured');
        }

        // Set required parameters for summarization
        $queryParams = array_merge([
            'summarize' => 'true',
            'language' => 'en',
        ], $options);

        $queryString = http_build_query($queryParams);

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post($baseUrl . '/read?' . $queryString, $payload);

        return $response->throw()->json();
    }
}
