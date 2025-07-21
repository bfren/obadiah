<?php

namespace Obadiah\Pages\Parts\Input;

use Obadiah\App;

App::check();

/**
 * @template T of boolean|int|string
 */
class Input_Model
{
    /**
     * Create Input Model.
     *
     * @param string $name      Input field name.
     * @param T $value          Input field value.
     * @return void
     */
    function __construct(
        public readonly string $name,
        public readonly mixed $value
    ) {}
}
