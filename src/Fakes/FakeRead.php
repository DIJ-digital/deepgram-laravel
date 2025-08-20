<?php

declare(strict_types=1);

namespace DIJ\Deepgram\Fakes;

class FakeRead
{
    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function summarizeText(string $text, array $options = []): array
    {
        return $this->generateFakeResponse();
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function summarizeUrl(string $url, array $options = []): array
    {
        return $this->generateFakeResponse();
    }

    /**
     * @return array<string, mixed>
     */
    private function generateFakeResponse(): array
    {
        return [
            'metadata' => [
                'request_id' => 'fake-request-' . uniqid(),
                'created' => date('c'),
                'language' => 'en',
                'summary_info' => [
                    'model_uuid' => 'fake-model-' . uniqid(),
                    'input_tokens' => 100,
                    'output_tokens' => 25,
                ],
            ],
            'results' => [
                'summary' => [
                    'text' => 'This is a fake summary of the provided content.',
                ],
            ],
        ];
    }
}
