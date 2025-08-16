<?php

declare(strict_types=1);

namespace DIJ\Deepgram\Fakes;

class FakeListen
{
    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function transcribeFile(string $absoluteFilePath, string $mimeType = 'audio/wav', array $options = []): array
    {
        return [
            'metadata' => [
                'transaction_key' => 'fake-transaction-' . uniqid(),
                'request_id' => 'fake-request-' . uniqid(),
                'sha256' => 'fake-sha256-hash',
                'created' => date('c'),
                'duration' => 10.5,
                'channels' => 1,
            ],
            'results' => [
                'channels' => [
                    [
                        'alternatives' => [
                            [
                                'transcript' => 'This is a fake transcription result.',
                                'confidence' => 0.99,
                                'words' => [
                                    ['word' => 'This', 'start' => 0.0, 'end' => 0.3, 'confidence' => 0.99],
                                    ['word' => 'is', 'start' => 0.3, 'end' => 0.4, 'confidence' => 0.99],
                                    ['word' => 'a', 'start' => 0.4, 'end' => 0.5, 'confidence' => 0.99],
                                    ['word' => 'fake', 'start' => 0.5, 'end' => 0.8, 'confidence' => 0.99],
                                    ['word' => 'transcription', 'start' => 0.8, 'end' => 1.4, 'confidence' => 0.99],
                                    ['word' => 'result.', 'start' => 1.4, 'end' => 1.8, 'confidence' => 0.99],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
