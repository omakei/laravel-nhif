<?php

namespace Omakei\LaravelNhif\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Omakei\LaravelNhif\LaravelNHIF
 */
class LaravelNHIF extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-nhif';
    }
}
