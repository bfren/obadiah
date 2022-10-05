<?php

namespace Feeds\Config;

use DateTimeZone;
use Feeds\App;
use Feeds\Helpers\Arr;

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
        $this->default_location = Arr::get($config, "default_location", "Church");
        $this->length_in_minutes = Arr::get($config, "length_in_minutes", 60);
        $this->timezone = new DateTimeZone(Arr::get($config, "timezone", "Europe/London"));
    }
}
