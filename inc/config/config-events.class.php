<?php

namespace Feeds\Config;

use DateTimeZone;

class Config_Events
{
    /**
     * Default length of events in minutes.
     *
     * @var int
     */
    public int $length_in_minutes;

    /**
     * Default timezone.
     *
     * @var DateTimeZone
     */
    public DateTimeZone $timezone;

    /**
     * Get values from events configuration array.
     *
     * @param array $config             Events configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->length_in_minutes = $config["length_in_minutes"];
        $this->timezone = new DateTimeZone($config["timezone"]);
    }
}
