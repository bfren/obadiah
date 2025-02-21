<?php

namespace Obadiah\Config;

use Obadiah\App;

App::check();

abstract class Config_Section
{
    /**
     * Return the config values as an array, ready to be serialised.
     *
     * @return array            Config values as an array.
     */
    abstract public function as_array(): array;
}