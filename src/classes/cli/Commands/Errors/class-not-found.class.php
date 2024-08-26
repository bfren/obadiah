<?php

namespace Obadiah\Cli\Commands\Errors;

use Obadiah\App;
use Obadiah\Cli\Command;

App::check();

class Class_Not_Found extends Command
{
    /**
     *
     * Executed when the mapped Command class cannot be found.
     *
     * @param string $command_class         Command class name.
     * @return void
     */
    public function __construct(
        public readonly string $command_class
    ) {}

    /**
     * Output error message and exit.
     *
     * @return void
     */
    public function execute(): void
    {
        _l("Error loading %s.", $this->command_class);
        exit;
    }
}
