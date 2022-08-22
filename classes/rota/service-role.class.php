<?php

namespace Feeds\Rota;

use Feeds\App;

App::check();

class Service_Role
{
    public function __construct(
        public readonly ?string $abbreviation,
        public readonly array $people
    ) {
    }
}
