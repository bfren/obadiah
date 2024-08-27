<?php

namespace Obadiah\Response;

use Obadiah\App;

App::check();

class Header
{
    /**
     * Create header object.
     *
     * @param string $key               Header key.
     * @param string $value             Header value.
     * @return void
     */
    public function __construct(
        public readonly string $key,
        public readonly string $value,
    ) {}
}
