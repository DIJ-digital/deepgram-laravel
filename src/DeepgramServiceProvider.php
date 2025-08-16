<?php

namespace DIJ\Deepgram;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use DIJ\Deepgram\Commands\DeepgramCommand;

class DeepgramServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('deepgram-laravel')
            ->hasConfigFile();
    }
}
