<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class Config_General
{
    /**
     * The name of your church (e.g. 'Christ Church').
     *
     * @var string
     */
    public readonly string $church_name;

    /**
     * The full name of your church (e.g. 'Christ Church Selly Park).
     *
     * @var string
     */
    public readonly string $church_name_full;

    /**
     * Your church's website domain name (do *not* include https://).
     *
     * @var string
     */
    public readonly string $church_domain;

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
        $this->church_name = Arr::get($config, "church_name", "");
        $this->church_name_full = Arr::get($config, "church_name_full", "");
        $this->church_domain = Arr::get($config, "church_domain", "");
        $this->production = Arr::get_boolean($config, "production", true);
    }
}
