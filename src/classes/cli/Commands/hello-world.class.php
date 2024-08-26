<?php

namespace Obadiah\Cli\Commands;

use Obadiah\App;
use Obadiah\Cli\Argument;
use Obadiah\Cli\Command;

App::check();

class Hello_World extends Command
{
    /**
     * Create command.
     *
     * @param string $person                The person to say hello to.
     * @return void
     */
    public function __construct(
        #[Argument("The name of the person to say hello to.", long: "to", required: true)]
        public readonly string $person
    ) {}

    /**
     * Say hello!
     *
     * @return void
     */
    public function execute(): void
    {
        echo ("Hello, $this->person!");
    }
}
