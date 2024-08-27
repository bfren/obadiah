<?php

namespace Obadiah\Pages\Parts\Header;

use Obadiah\App;

App::check();

class Header_Model
{
    /**
     * Create Header model.
     *
     * @param string $title             Page title.
     * @param string|null $subtitle     Optional page subtitle.
     * @param string|null $class        Optional class for HTML tag.
     * @param bool $overflow_scroll     Enable overflow scroll for this page.
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $subtitle = null,
        public readonly ?string $class = null,
        public readonly bool $overflow_scroll = false
    ) {}
}
