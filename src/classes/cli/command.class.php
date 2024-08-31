<?php

namespace Obadiah\Cli;

use Obadiah\App;
use Throwable;

App::check();

abstract class Command
{
    /**
     * Execute command and exit, catching and logging any errors.
     *
     * @return never
     */
    final public function try_execute(): void
    {
        // attempt to execute the current command and exit
        try {
            $this->execute();
        } catch (Throwable $th) {
            _l_throwable($th);
        } finally {
            exit;
        }
    }

    /**
     * Execute command.
     *
     * @return void
     */
    abstract public function execute(): void;
}
