<?php

namespace Obadiah\Cli\Commands\Errors;

use Obadiah\App;
use Obadiah\Cli\Command;

App::check();

class Unknown extends Command
{
    /**
     * Executed when a command has been called and is not mapped to an implementation class.
     *
     * @param string $command_name          Command name.
     * @return void
     */
    public function __construct(
        public readonly string $command_name
    ) {}

    /**
     * Output error message and exit.
     *
     * @return void
     */
    public function execute(): void
    {
        _l("Unknown command: %s.", $this->command_name);
        exit;
    }
}
