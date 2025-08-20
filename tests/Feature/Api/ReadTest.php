<?php

declare(strict_types=1);

use DIJ\Deepgram\Exceptions\DeepgramConfigurationException;
use DIJ\Deepgram\Facades\Deepgram;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    // ARRANGE - Set up config for each test
    config([
        'deepgram-laravel.api_key' => 'test-api-key',
        'deepgram-laravel.base_url' => 'https://api.deepgram.com/v1',
    ]);
});

describe('Read API', function (): void {
    describe('summarizeText', function (): void {
        describe('success cases', function (): void {
            it('can summarize text with default options', function (): void {
                // ARRANGE
                $inputText = 'This is a long piece of text that needs to be summarized. It contains multiple sentences and various information that should be condensed into a shorter summary.';

                Http::fake([
                    'api.deepgram.com/v1/read*' => Http::response([
                        'metadata' => [
                            'request_id' => '12345',
                            'created' => '2024-01-01T10:00:00.000Z',
                            'language' => 'en',
                            'summary_info' => [
                                'model_uuid' => 'abc123',
                                'input_tokens' => 25,
                                'output_tokens' => 15,
                            ],
                        ],
                        'results' => [
                            'summary' => [
                                'text' => 'This text discusses various information that should be condensed.',
                            ],
                        ],
                    ], 200),
                ]);

                // ACT
                $result = Deepgram::read()->summarizeText($inputText);

                // ASSERT
                expect($result)->toBeArray()
                    ->and($result['results']['summary']['text'])
                    ->toBe('This text discusses various information that should be condensed.')
                    ->and($result['metadata']['summary_info']['input_tokens'])
                    ->toBe(25);

                // Verify HTTP request was made correctly
                Http::assertSent(fn ($request): bool => $request->url() === 'https://api.deepgram.com/v1/read?summarize=true&language=en'
                    && $request->header('Authorization')[0] === 'Token test-api-key'
                    && $request->header('Content-Type')[0] === 'application/json'
                    && $request->data() === ['text' => $inputText]
                );
            });

            it('can summarize text with custom options', function (): void {
                // ARRANGE
                $inputText = 'Custom text to summarize with additional options.';

                Http::fake([
                    'api.deepgram.com/v1/read*' => Http::response([
                        'metadata' => ['request_id' => '67890'],
                        'results' => [
                            'summary' => [
                                'text' => 'Custom summary result.',
                            ],
                        ],
                    ], 200),
                ]);

                // ACT
                $result = Deepgram::read()->summarizeText($inputText, ['language' => 'en-US']);

                // ASSERT
                expect($result)->toBeArray()
                    ->and($result['results']['summary']['text'])
                    ->toBe('Custom summary result.');

                // Verify custom options were sent
                Http::assertSent(fn ($request): bool => str_contains((string) $request->url(), 'language=en-US')
                    && str_contains((string) $request->url(), 'summarize=true')
                );
            });
        });

        describe('error handling', function (): void {
            it('throws configuration exception when api key is missing', function (): void {
                // ARRANGE
                config(['deepgram-laravel.api_key' => '']);

                // ACT & ASSERT
                expect(fn () => Deepgram::read()->summarizeText('Test text'))
                    ->toThrow(DeepgramConfigurationException::class, 'Deepgram API key is not properly configured');
            });

            it('throws configuration exception when base url is empty', function (): void {
                // ARRANGE
                config(['deepgram-laravel.base_url' => '']);

                // ACT & ASSERT
                expect(fn () => Deepgram::read()->summarizeText('Test text'))
                    ->toThrow(DeepgramConfigurationException::class, 'Deepgram base URL is not properly configured');
            });

            it('throws request exception when api returns error', function (): void {
                // ARRANGE
                Http::fake([
                    'api.deepgram.com/v1/read*' => Http::response([
                        'err_code' => 'TOKEN_LIMIT_EXCEEDED',
                        'err_msg' => 'Text input exceeds maximum token limit.',
                    ], 400),
                ]);

                // ACT & ASSERT
                expect(fn () => Deepgram::read()->summarizeText('Test text'))
                    ->toThrow(RequestException::class);
            });
        });
    });

    describe('summarizeUrl', function (): void {
        describe('success cases', function (): void {
            it('can summarize text from URL with default options', function (): void {
                // ARRANGE
                $textUrl = 'https://example.com/sample.txt';

                Http::fake([
                    'api.deepgram.com/v1/read*' => Http::response([
                        'metadata' => [
                            'request_id' => '67890',
                            'created' => '2024-01-01T10:00:00.000Z',
                            'language' => 'en',
                            'summary_info' => [
                                'model_uuid' => 'def456',
                                'input_tokens' => 50,
                                'output_tokens' => 20,
                            ],
                        ],
                        'results' => [
                            'summary' => [
                                'text' => 'This is a summary from the URL content.',
                            ],
                        ],
                    ], 200),
                ]);

                // ACT
                $result = Deepgram::read()->summarizeUrl($textUrl);

                // ASSERT
                expect($result)->toBeArray()
                    ->and($result['results']['summary']['text'])
                    ->toBe('This is a summary from the URL content.')
                    ->and($result['metadata']['summary_info']['input_tokens'])
                    ->toBe(50);

                // Verify HTTP request was made correctly
                Http::assertSent(fn ($request): bool => $request->url() === 'https://api.deepgram.com/v1/read?summarize=true&language=en'
                    && $request->header('Authorization')[0] === 'Token test-api-key'
                    && $request->header('Content-Type')[0] === 'application/json'
                    && $request->data() === ['url' => $textUrl]
                );
            });
        });

        describe('error handling', function (): void {
            it('throws configuration exception when api key is missing', function (): void {
                // ARRANGE
                config(['deepgram-laravel.api_key' => '']);

                // ACT & ASSERT
                expect(fn () => Deepgram::read()->summarizeUrl('https://example.com/test.txt'))
                    ->toThrow(DeepgramConfigurationException::class, 'Deepgram API key is not properly configured');
            });
        });
    });
});
