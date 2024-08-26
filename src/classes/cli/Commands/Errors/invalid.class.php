<?php

namespace Obadiah\Cli\Commands\Errors;

use Obadiah\App;
use Obadiah\Cli\Command;

App::check();

class Invalid extends Command
{
    /**
     * Executed when something goes wrong creating an instance of a command.
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
     * @return never
     */
    public function execute(): void
    {
        App::die("Error creating %s.", $this->command_class);
    }
}
