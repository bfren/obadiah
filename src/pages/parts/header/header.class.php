<?php

namespace Feeds\Pages\Parts\Header;

use Feeds\App;

App::check();

class Header
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $subtitle = null,
        public readonly bool $overflow_scroll = false
    ) {
    }
}
