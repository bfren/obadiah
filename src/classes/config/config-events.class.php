<?php

namespace Feeds\Config;

use DateTimeZone;
use Feeds\App;

App::check();

class Config_Events
{
    /**
     * Default length of events in minutes.
     *
     * @var int
     */
    public readonly int $length_in_minutes;

    /**
     * Get values from events configuration array.
     *
     * @param array $config             Events configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->length_in_minutes = $config["length_in_minutes"];
    }
}
