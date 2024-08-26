<?php

namespace Obadiah\Cli;

use Attribute;
use Obadiah\App;

App::check();

#[Attribute]
class Argument
{
    /**
     * Define a commandline argument.
     *
     * @param string|null $long         Override long name, to be used as --property.
     * @param string|null $short        Optional short name, e.g. -p.
     * @param bool $required            Whether or not this parameter is required.
     * @return void
     */
    public function __construct(
        public readonly string $description,
        public readonly ?string $long = null,
        public readonly ?string $short = null,
        public readonly bool $required = false
    ) {}
}
