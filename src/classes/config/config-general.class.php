<?php

namespace Feeds\Config;

use Feeds\App;
use Feeds\Helpers\Arr;

App::check();

class Config_General
{
    /**
     * Whether or not the app is in production mode.
     *
     * @var bool
     */
    public readonly bool $production;

    /**
     * Get values from general configuration array.
     *
     * @param array $config             General configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->production = Arr::get_boolean($config, "production", true);
    }
}
