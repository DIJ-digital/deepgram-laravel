<?php

declare(strict_types=1);

use DIJ\Deepgram\Exceptions\DeepgramConfigurationException;
use DIJ\Deepgram\Facades\Deepgram;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToReadFile;

beforeEach(function (): void {
    // ARRANGE - Set up fake storage and config for each test
    Storage::fake('local');

    config([
        'deepgram-laravel.api_key' => 'test-api-key',
        'deepgram-laravel.base_url' => 'https://api.deepgram.com/v1',
        'deepgram-laravel.default_model' => 'nova-2',
        'deepgram-laravel.default_language' => 'nl',
    ]);
});

describe('Listen API', function (): void {
    describe('transcribeFile', function (): void {
        describe('success cases', function (): void {
            it('can transcribe audio file with default config', function (): void {
                // ARRANGE
                Storage::put('test-audio.wav', 'fake-audio-content');
                $audioFile = Storage::path('test-audio.wav');

                Http::fake([
                    'api.deepgram.com/v1/listen*' => Http::response([
                        'metadata' => [
                            'transaction_key' => 'deprecated',
                            'request_id' => '12345',
                            'sha256' => 'abc123',
                            'created' => '2024-01-01T10:00:00.000Z',
                            'duration' => 12.5,
                            'channels' => 1,
                        ],
                        'results' => [
                            'channels' => [
                                [
                                    'alternatives' => [
                                        [
                                            'transcript' => 'This is a test transcription.',
                                            'confidence' => 0.95,
                                            'words' => [],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ], 200),
                ]);

                // ACT
                $result = Deepgram::listen()->transcribeFile($audioFile, 'audio/wav');

                // ASSERT
                expect($result)->toBeArray()
                    ->and($result['results']['channels'][0]['alternatives'][0]['transcript'])
                    ->toBe('This is a test transcription.')
                    ->and($result['results']['channels'][0]['alternatives'][0]['confidence'])
                    ->toBe(0.95)
                    ->and($result['metadata']['duration'])
                    ->toBe(12.5);

                // Verify HTTP request was made correctly
                Http::assertSent(fn ($request): bool => $request->url() === 'https://api.deepgram.com/v1/listen?model=nova-2&language=nl'
                    && $request->header('Authorization')[0] === 'Token test-api-key'
                    && $request->header('Content-Type')[0] === 'audio/wav');
            });

            it('can transcribe with custom options', function (): void {
                // ARRANGE
                Storage::put('custom-audio.wav', 'fake-audio-content');
                $audioFile = Storage::path('custom-audio.wav');

                Http::fake([
                    'api.deepgram.com/v1/listen*' => Http::response([
                        'metadata' => ['duration' => 8.2],
                        'results' => [
                            'channels' => [
                                [
                                    'alternatives' => [
                                        [
                                            'transcript' => 'Custom transcription with diarization.',
                                            'confidence' => 0.98,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ], 200),
                ]);

                $customOptions = [
                    'model' => 'nova-3',
                    'language' => 'en',
                    'smart_format' => true,
                    'punctuate' => true,
                    'diarize' => true,
                ];

                // ACT
                $result = Deepgram::listen()->transcribeFile($audioFile, 'audio/wav', $customOptions);

                // ASSERT
                expect($result)->toBeArray()
                    ->and($result['results']['channels'][0]['alternatives'][0]['transcript'])
                    ->toBe('Custom transcription with diarization.');

                // Verify custom options were sent in query string
                Http::assertSent(function ($request): bool {
                    $url = $request->url();

                    return str_contains($url, 'model=nova-3')
                        && str_contains($url, 'language=en')
                        && str_contains($url, 'smart_format=1')
                        && str_contains($url, 'punctuate=1')
                        && str_contains($url, 'diarize=1');
                });
            });

            it('handles different mime types correctly', function (): void {
                // ARRANGE
                Storage::put('mime-test.mp3', 'fake-mp3-content');
                $audioFile = Storage::path('mime-test.mp3');

                Http::fake([
                    'api.deepgram.com/v1/listen*' => Http::response([
                        'metadata' => ['duration' => 3.5],
                        'results' => [
                            'channels' => [
                                [
                                    'alternatives' => [
                                        [
                                            'transcript' => 'MP3 file transcribed.',
                                            'confidence' => 0.89,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ], 200),
                ]);

                // ACT
                $result = Deepgram::listen()->transcribeFile($audioFile, 'audio/mpeg');

                // ASSERT
                expect($result)->toBeArray();

                // Verify correct Content-Type header
                Http::assertSent(fn ($request): bool => $request->header('Content-Type')[0] === 'audio/mpeg');
            });
        });

        describe('error handling', function (): void {
            it('throws configuration exception when api key is missing', function (): void {
                // ARRANGE
                config(['deepgram-laravel.api_key' => '']);
                Storage::put('config-test.wav', 'fake-audio-content');
                $audioFile = Storage::path('config-test.wav');

                // ACT & ASSERT
                expect(fn () => Deepgram::listen()->transcribeFile($audioFile))
                    ->toThrow(DeepgramConfigurationException::class, 'Deepgram API key is not properly configured');
            });

            it('throws configuration exception when base url is empty', function (): void {
                // ARRANGE
                config(['deepgram-laravel.base_url' => '']);
                Storage::put('base-url-test.wav', 'fake-audio-content');
                $audioFile = Storage::path('base-url-test.wav');

                // ACT & ASSERT
                expect(fn () => Deepgram::listen()->transcribeFile($audioFile))
                    ->toThrow(DeepgramConfigurationException::class, 'Deepgram base URL is not properly configured');
            });

            it('throws exception when audio file is not readable', function (): void {
                // ARRANGE
                $nonExistentFile = Storage::path('non-existent.wav'); // File doesn't exist in fake storage

                // ACT & ASSERT
                expect(fn () => Deepgram::listen()->transcribeFile($nonExistentFile))
                    ->toThrow(UnableToReadFile::class, 'Audio file not readable');
            });

            it('throws request exception when api returns error', function (): void {
                // ARRANGE
                Storage::put('error-test.wav', 'fake-audio-content');
                $audioFile = Storage::path('error-test.wav');

                Http::fake([
                    'api.deepgram.com/v1/listen*' => Http::response([
                        'error' => 'Invalid audio format',
                    ], 400),
                ]);

                // ACT & ASSERT
                expect(fn () => Deepgram::listen()->transcribeFile($audioFile))
                    ->toThrow(RequestException::class);
            });
        });

        describe('options and configuration', function (): void {
            it('merges options correctly with config defaults', function (): void {
                // ARRANGE
                Storage::put('merge-test.wav', 'fake-audio-content');
                $audioFile = Storage::path('merge-test.wav');

                Http::fake([
                    'api.deepgram.com/v1/listen*' => Http::response([
                        'metadata' => ['duration' => 2.0],
                        'results' => [
                            'channels' => [
                                [
                                    'alternatives' => [
                                        [
                                            'transcript' => 'Options merged correctly.',
                                            'confidence' => 0.97,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ], 200),
                ]);

                // ACT - Only override language, keep default model
                $result = Deepgram::listen()->transcribeFile($audioFile, 'audio/wav', [
                    'language' => 'en',
                    'punctuate' => true,
                ]);

                // ASSERT
                expect($result)->toBeArray();

                // Verify that config defaults are kept and options are merged
                Http::assertSent(function ($request): bool {
                    $url = $request->url();

                    return str_contains($url, 'model=nova-2') // from config
                        && str_contains($url, 'language=en') // from options
                        && str_contains($url, 'punctuate=1'); // from options
                });
            });
        });
    });
});
