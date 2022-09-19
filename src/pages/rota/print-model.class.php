<?php

namespace Feeds\Pages\Rota;

use Feeds\App;

App::check();

class Print_Model
{
    public function __construct(
        public readonly ?string $time,
        public readonly ?string $day,
        public readonly ?string $person,
        public readonly array $days
    ) {
    }
}
