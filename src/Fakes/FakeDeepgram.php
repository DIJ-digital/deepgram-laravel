<?php

declare(strict_types=1);

namespace DIJ\Deepgram\Fakes;

use PHPUnit\Framework\Assert;

class FakeDeepgram
{
    protected FakeListen $fakeListen;

    protected FakeSpeak $fakeSpeak;

    protected FakeRead $fakeRead;

    public function __construct()
    {
        $this->fakeListen = new FakeListen();
        $this->fakeSpeak = new FakeSpeak();
        $this->fakeRead = new FakeRead();
    }

    public function listen(): FakeListen
    {
        return $this->fakeListen;
    }

    public function speak(): FakeSpeak
    {
        return $this->fakeSpeak;
    }

    public function read(): FakeRead
    {
        return $this->fakeRead;
    }

    /* -------------------- Fake Listen -------------------- */

    // Convenience methods for direct assertions on the main fake instance
    public function expectNoTranscriptionsCalled(): void
    {
        Assert::assertEquals(
            0,
            count($this->fakeListen->getTranscriptions()),
            'Expected no transcriptions to be called, but found: ' . json_encode($this->fakeListen->getTranscriptions())
        );
    }

    public function expectNumberOfTranscriptionsCalled(int $expectedCount): void
    {
        $actualCount = count($this->fakeListen->getTranscriptions());

        Assert::assertEquals(
            $expectedCount,
            $actualCount,
            "Expected {$expectedCount} transcriptions to be called, but {$actualCount} were actually called"
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getTranscriptions(): array
    {
        return $this->fakeListen->getTranscriptions();
    }

    public function clearTranscriptions(): void
    {
        $this->fakeListen->clearTranscriptions();
    }

    /* -------------------- Fake Speak -------------------- */

    /* -------------------- Fake Read -------------------- */

}
