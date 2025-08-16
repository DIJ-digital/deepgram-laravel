<?php

declare(strict_types=1);

namespace DIJ\Deepgram\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DIJ\Deepgram\Deepgram
 */
final class Deepgram extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DIJ\Deepgram\Deepgram::class;
    }
}
