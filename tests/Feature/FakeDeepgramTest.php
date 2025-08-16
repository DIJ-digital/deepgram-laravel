<?php

declare(strict_types=1);

use DIJ\Deepgram\Facades\Deepgram;
use DIJ\Deepgram\Fakes\FakeListen;
use DIJ\Deepgram\Fakes\FakeRead;
use DIJ\Deepgram\Fakes\FakeSpeak;

describe('Fake Deepgram', function (): void {
    it('can fake deepgram transcriptions', function (): void {
        // Arrange
        $fake = Deepgram::fake();
        $testFilePath = '/path/to/test/audio.wav';
        $testOptions = ['model' => 'nova-2', 'language' => 'en-US'];

        // Act
        $result = Deepgram::listen()->transcribeFile($testFilePath, 'audio/wav', $testOptions);

        // Assert response
        expect($result)->toBeArray()
            ->toHaveKey('metadata')
            ->toHaveKey('results')
            ->and($result['results']['channels'][0]['alternatives'][0]['transcript'])
            ->toBe('This is a fake transcription result.');

        // Assert calls made - simple array checks
        $transcriptions = $fake->listen()->getTranscriptions();
        expect($transcriptions)->toHaveCount(1)
            ->and($transcriptions[0]['file_path'])->toBe($testFilePath)
            ->and($transcriptions[0]['options'])->toBe($testOptions);
    });

    it('can assert no transcriptions called', function (): void {
        // Arrange
        $fake = Deepgram::fake();

        // Assert - just check empty array
        expect($fake->listen()->getTranscriptions())->toHaveCount(0);
    });

    it('can assert multiple transcriptions', function (): void {
        // Arrange
        $fake = Deepgram::fake();

        // Act
        Deepgram::listen()->transcribeFile('/path/to/audio1.wav');
        Deepgram::listen()->transcribeFile('/path/to/audio2.wav');
        Deepgram::listen()->transcribeFile('/path/to/audio3.wav');

        // Assert - just count
        expect($fake->listen()->getTranscriptions())->toHaveCount(3);
    });

    it('can access raw transcription data', function (): void {
        // Arrange
        $fake = Deepgram::fake();

        // Act
        Deepgram::listen()->transcribeFile('/path/to/audio.mp3', 'audio/mp3', ['model' => 'whisper']);

        // Assert - check specific details
        $transcriptions = $fake->listen()->getTranscriptions();
        expect($transcriptions[0]['mime_type'])->toBe('audio/mp3')
            ->and($transcriptions[0]['options']['model'])->toBe('whisper');
    });

    it('can clear transcriptions', function (): void {
        // Arrange
        $fake = Deepgram::fake();

        // Act
        Deepgram::listen()->transcribeFile('/test.wav');
        $fake->listen()->clearTranscriptions();

        // Assert
        expect($fake->listen()->getTranscriptions())->toHaveCount(0);
    });

    it('returns fake instances for all APIs', function (): void {
        // Arrange
        $fake = Deepgram::fake();

        // Assert - All APIs should return fake instances after faking
        expect($fake->listen())->toBeInstanceOf(FakeListen::class)
            ->and($fake->speak())->toBeInstanceOf(FakeSpeak::class)
            ->and($fake->read())->toBeInstanceOf(FakeRead::class);
    });

    it('supports mockery shouldReceive for mocking', function (): void {
        // Arrange - Mock instead of fake
        Deepgram::shouldReceive('listen->transcribeFile')
            ->once()
            ->with('/test.wav', 'audio/wav', ['model' => 'nova-2'])
            ->andReturn([
                'metadata' => ['duration' => 5.0],
                'results' => [
                    'channels' => [
                        [
                            'alternatives' => [
                                ['transcript' => 'Mocked transcription result', 'confidence' => 1.0],
                            ],
                        ],
                    ],
                ],
            ]);

        // Act - Use normal Deepgram calls
        $result = Deepgram::listen()->transcribeFile('/test.wav', 'audio/wav', ['model' => 'nova-2']);

        // Assert - Check mocked response
        expect($result['results']['channels'][0]['alternatives'][0]['transcript'])
            ->toBe('Mocked transcription result');
    });
});
