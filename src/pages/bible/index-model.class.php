<?php

namespace Obadiah\Pages\Bible;

use Obadiah\App;

App::check();

class Index_Model
{
    /**
     * Create Index model.
     *
     * @param @string $ref                          The Bible passage reference.
     * @param ?string $text                         The text of the requested Bible passage.
     */
    public function __construct(
        public readonly ?string $ref,
        public readonly ?string $text
    ) {}
}
