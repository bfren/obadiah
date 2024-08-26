<?php

namespace Obadiah\Cli;

use Obadiah\App;

App::check();

abstract class Command
{
    abstract public function execute(): void;
}
