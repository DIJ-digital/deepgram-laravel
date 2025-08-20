<?php

declare(strict_types=1);

use DIJ\Deepgram\Facades\Deepgram;
use DIJ\Deepgram\Fakes\FakeListen;
use DIJ\Deepgram\Fakes\FakeRead;
use DIJ\Deepgram\Fakes\FakeSpeak;

describe('Fake Deepgram', function (): void {
    it('can fake deepgram to prevent HTTP calls', function (): void {
        // Arrange
        Deepgram::fake();

        // Act - No real HTTP calls will be made
        $result = Deepgram::listen()->transcribeFile('/path/to/audio.wav');

        // Assert - Returns fake data
        expect($result)->toBeArray()
            ->toHaveKey('metadata')
            ->toHaveKey('results')
            ->and($result['results']['channels'][0]['alternatives'][0]['transcript'])
            ->toBe('This is a fake transcription result.');
    });

    it('returns fake instances for all APIs', function (): void {
        // Arrange
        $fake = Deepgram::fake();

        // Assert - All APIs return fake instances
        expect($fake->listen())->toBeInstanceOf(FakeListen::class)
            ->and($fake->speak())->toBeInstanceOf(FakeSpeak::class)
            ->and($fake->read())->toBeInstanceOf(FakeRead::class);
    });

    it('supports mockery shouldReceive for custom responses', function (): void {
        // Arrange - Mock custom response
        Deepgram::shouldReceive('listen->transcribeFile')
            ->once()
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

        // Act
        $result = Deepgram::listen()->transcribeFile('/test.wav');

        // Assert - Gets the mocked response
        expect($result['results']['channels'][0]['alternatives'][0]['transcript'])
            ->toBe('Custom mocked result');
    });

    it('can fake read API for text summarization', function (): void {
        // Arrange
        Deepgram::fake();

        // Act - No real HTTP calls will be made
        $result = Deepgram::read()->summarizeText('This is a long text that needs to be summarized for testing purposes.');

        // Assert - Returns fake summary data
        expect($result)->toBeArray()
            ->toHaveKey('metadata')
            ->toHaveKey('results')
            ->and($result['results']['summary']['text'])
            ->toBe('This is a fake summary of the provided text content.');
    });
});
