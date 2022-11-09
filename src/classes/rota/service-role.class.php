<?php

namespace Feeds\Rota;

use Feeds\App;

App::check();

class Service_Role
{
    /**
     * Create Service_Role object.
     *
     * @param ?string $abbreviation             Role abbreviation.
     * @param string[] $people                  Names of the people with this role.
     */
    public function __construct(
        public readonly ?string $abbreviation,
        public readonly array $people
    ) {
    }
}
