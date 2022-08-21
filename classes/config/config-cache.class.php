<?php

namespace Feeds\Config;

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
        $this->duration_in_seconds = $config["duration_in_seconds"];
    }
}
