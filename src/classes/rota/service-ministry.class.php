<?php

namespace Obadiah\Rota;

use Obadiah\App;

App::check();

class Service_Ministry
{
    /**
     * Create Service_Ministry object.
     *
     * @param ?string $abbreviation             Ministry abbreviation.
     * @param string[] $people                  Names of the people with this ministry.
     */
    public function __construct(
        public readonly ?string $abbreviation,
        public readonly array $people
    ) {}
}
