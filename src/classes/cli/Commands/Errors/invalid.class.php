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
    private function __construct(
        public readonly string $command_class
    ) {}

    /**
     * Output error message and exit.
     *
     * @return void
     */
    public function execute(): void
    {
        _l("Error creating %s.", $this->command_class);
        exit;
    }
}
