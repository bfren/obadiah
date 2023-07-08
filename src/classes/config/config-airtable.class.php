<?php

namespace Feeds\Config;

use Feeds\App;
use Feeds\Helpers\Arr;

App::check();

class Config_Airtable
{
    /**
     * Base reference.
     *
     * @var string
     */
    public readonly string $base;

    /**
     * Personal Access Token.
     *
     * @var string
     */
    public readonly string $personal_access_token;

    /**
     * Get values from Airtable configuration array.
     *
     * @param array $config             Airtable configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->base = Arr::get($config, "base", "");
        $this->personal_access_token = Arr::get($config, "personal_access_token", "");
    }
}
