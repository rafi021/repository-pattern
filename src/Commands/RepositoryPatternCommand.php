<?php

namespace Rafi021\RepositoryPattern\Commands;

use Illuminate\Console\Command;

class RepositoryPatternCommand extends Command
{
    public $signature = 'repository-pattern';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
