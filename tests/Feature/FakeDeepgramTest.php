<?php

declare(strict_types=1);

use DIJ\Deepgram\Facades\Deepgram;
use DIJ\Deepgram\Fakes\FakeListen;
use DIJ\Deepgram\Fakes\FakeRead;
use DIJ\Deepgram\Fakes\FakeSpeak;

describe('Fake Deepgram', function (): void {

    beforeEach(function (): void {
        Deepgram::fake();
    });

    it('can fake deepgram transcriptions', function (): void {
        // Arrange
        $testFilePath = '/path/to/test/audio.wav';
        $testOptions = ['model' => 'nova-2', 'language' => 'en-US'];

        // Act
        $result = Deepgram::listen()->transcribeFile($testFilePath, 'audio/wav', $testOptions);

        // Assert
        expect($result)->toBeArray()
            ->toHaveKey('metadata')
            ->toHaveKey('results')
            ->and($result['results']['channels'][0]['alternatives'][0]['transcript'])
            ->toBe('This is a fake transcription result.');

        // Test assertion methods - directly on facade like SlackAlert
        Deepgram::expectNumberOfTranscriptionsCalled(1);
    });

    it('can assert no transcriptions called', function (): void {
        // Assert
        Deepgram::expectNoTranscriptionsCalled();
    });

    it('can assert multiple transcriptions', function (): void {
        // Act
        Deepgram::listen()->transcribeFile('/path/to/audio1.wav');
        Deepgram::listen()->transcribeFile('/path/to/audio2.wav');
        Deepgram::listen()->transcribeFile('/path/to/audio3.wav');

        // Assert
        Deepgram::expectNumberOfTranscriptionsCalled(3);
    });

    it('can access raw transcription data', function (): void {
        // Act
        Deepgram::listen()->transcribeFile('/path/to/audio.mp3', 'audio/mp3', ['model' => 'whisper']);

        // Assert - Check raw data if you need specific details
        $transcriptions = Deepgram::getTranscriptions();
        expect($transcriptions[0]['mime_type'])->toBe('audio/mp3')
            ->and($transcriptions[0]['options']['model'])->toBe('whisper');
    });

    it('can access transcriptions directly', function (): void {
        // Act
        Deepgram::listen()->transcribeFile('/test.wav', 'audio/wav', ['language' => 'nl']);

        // Assert
        $transcriptions = Deepgram::getTranscriptions();
        expect($transcriptions)->toHaveCount(1)
            ->and($transcriptions[0]['file_path'])->toBe('/test.wav')
            ->and($transcriptions[0]['mime_type'])->toBe('audio/wav')
            ->and($transcriptions[0]['options'])->toBe(['language' => 'nl']);
    });

    it('returns fake instances for all APIs', function (): void {
        // Assert - All APIs should return fake instances after faking
        expect(Deepgram::listen())->toBeInstanceOf(FakeListen::class)
            ->and(Deepgram::speak())->toBeInstanceOf(FakeSpeak::class)
            ->and(Deepgram::read())->toBeInstanceOf(FakeRead::class);
    });
});
