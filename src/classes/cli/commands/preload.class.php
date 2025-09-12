<?php

namespace Obadiah\Cli\Commands;

use Obadiah\App;
use Obadiah\Cli\Command;
use Obadiah\Preload\Preload as P;
use Obadiah\Request\Request;

App::check();

class Preload extends Command
{
    /**
     * Create command.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Preload the caches.
     *
     * @return never
     */
    public function execute(): void
    {
        $execute = function (string $name, callable $function) {
            printf("%s... ", $name);
            $result = $function();
            printf("%s\n", json_encode($result));
        };

        $execute("Bible Reading Plan", fn() => P::get_bible_plan());
        $execute("Events", fn() => P::get_events());
        $execute("Lectionary", fn() => P::get_lectionary());
        $execute("People", fn() => P::get_people());
        $execute("Refresh Daily Prayers", fn() => P::get_refresh());
        $execute("Rota", fn() => P::get_rota());
    }


}
