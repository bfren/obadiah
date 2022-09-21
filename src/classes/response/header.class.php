<?php

namespace Feeds\Response;

use Feeds\App;

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
    ) {
    }
}
