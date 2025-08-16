<?php

declare(strict_types=1);

namespace DIJ\Deepgram\Facades;

use DIJ\Deepgram\Api\Listen;
use DIJ\Deepgram\Api\Read;
use DIJ\Deepgram\Api\Speak;
use DIJ\Deepgram\Fakes\FakeDeepgram;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Listen listen()
 * @method static Speak speak()
 * @method static Read read()
 * @method static void fake()
 *
 * @see \DIJ\Deepgram\Deepgram
 */
final class Deepgram extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DIJ\Deepgram\Deepgram::class;
    }

    public static function fake(): void
    {
        $fake = new FakeDeepgram();
        self::swap($fake);
    }
}
