<?php

namespace Omakei\LaravelNhif\Commands;

use Illuminate\Console\Command;

class LaravelNHIFCommand extends Command
{
    public $signature = 'laravel-nhif';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
