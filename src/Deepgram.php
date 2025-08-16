<?php

declare(strict_types=1);

namespace DIJ\Deepgram;

use DIJ\Deepgram\Api\Listen;
use DIJ\Deepgram\Api\Read;
use DIJ\Deepgram\Api\Speak;

final class Deepgram
{
    /**
     * Access the Listen API for Speech-to-Text functionality
     */
    public function listen(): Listen
    {
        return new Listen();
    }

    /**
     * Access the Speak API for Text-to-Speech functionality
     */
    public function speak(): Speak
    {
        return new Speak();
    }

    /**
     * Access the Read API for Text Intelligence functionality
     */
    public function read(): Read
    {
        return new Read();
    }
}
