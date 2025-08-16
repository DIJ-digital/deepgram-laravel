<?php

namespace DIJ\Deepgram\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DIJ\Deepgram\Deepgram
 */
class Deepgram extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DIJ\Deepgram\Deepgram::class;
    }
}
