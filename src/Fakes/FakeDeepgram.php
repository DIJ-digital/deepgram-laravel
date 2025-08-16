<?php

declare(strict_types=1);

namespace DIJ\Deepgram\Fakes;

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
}
