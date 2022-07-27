<?php

namespace Omakei\LaravelNhif;

use Omakei\LaravelNhif\Commands\LaravelNHIFCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelNhifServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-nhif')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-nhif_table')
            ->hasCommand(LaravelNHIFCommand::class);
    }
}
