<?php

declare(strict_types=1);

namespace DIJ\Deepgram;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class DeepgramServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatielaravel-package-tools
         */
        $package
            ->name('deepgram-laravel')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Deepgram::class, fn (): Deepgram => new Deepgram());
    }
}
