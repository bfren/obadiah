<?php

namespace Feeds\Config;

use DateTimeZone;
use Feeds\App;

App::check();

class Config_Events
{
    /**
     * Location to use in case it is not set for a service or event.
     *
     * @var string
     */
    public readonly string $default_location;

    /**
     * Default length of events in minutes.
     *
     * @var int
     */
    public readonly int $length_in_minutes;

    /**
     * Default timezone.
     *
     * @var DateTimeZone
     */
    public readonly DateTimeZone $timezone;

    /**
     * Get values from events configuration array.
     *
     * @param array $config             Events configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->default_location = $config["default_location"];
        $this->length_in_minutes = $config["length_in_minutes"];
        $this->timezone = new DateTimeZone($config["timezone"]);
    }
}
