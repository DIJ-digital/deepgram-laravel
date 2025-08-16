<?php

namespace DIJ\Deepgram\Commands;

use Illuminate\Console\Command;

class DeepgramCommand extends Command
{
    public $signature = 'deepgram-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
