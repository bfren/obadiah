<?php

namespace Obadiah\Cli\Commands\Errors;

use Obadiah\App;
use Obadiah\Cli\Command;

App::check();

class Argument_Missing extends Command
{
    /**
     * Executed when a Command requires an argument that is missing.
     *
     * @param string $command_name              Command name.
     * @param string $arg_long                  Long form of the missing argument.
     * @param null|string $arg_short            Optional short form of the missing argument.
     * @return void
     */
    public function __construct(
        public readonly string $command_name,
        public readonly string $arg_long,
        public readonly ?string $arg_short
    ) {}

    /**
     * Output error message and exit.
     *
     * @return void
     */
    public function execute(): void
    {
        _l("Command %s requires parameter %s%s.", $this->command_name, $this->arg_long, $this->arg_short == null ? "" : " | $this->arg_short");
        exit;
    }
}
