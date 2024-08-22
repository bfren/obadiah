<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class Config_Cache
{
    /**
     * Duration in seconds before cache entries expire.
     *
     * @var int
     */
    public readonly int $duration_in_seconds;

    /**
     * Get values from cache configuration array.
     *
     * @param array $config             Cache configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->duration_in_seconds = Arr::get_integer($config, "duration_in_seconds", 3600);
    }
}
