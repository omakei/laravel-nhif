<?php

namespace Omakei\LaravelNhif\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Omakei\LaravelNhif\LaravelNhifServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Omakei\\LaravelNHIF\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelNHIFServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-nhif_table.php.stub';
        $migration->up();
        */
    }
}
