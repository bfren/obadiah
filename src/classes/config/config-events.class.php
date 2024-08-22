<?php

namespace Obadiah\Config;

use DateTimeZone;
use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class Config_Events
{
    /**
     * Prepended to events that have a status of 'cancelled'.
     *
     * @var string
     */
    public readonly string $cancelled_flag;

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
     * Prepended to events that have a status of 'pending'.
     *
     * @var string
     */
    public readonly string $pending_flag;

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
        $this->cancelled_flag = Arr::get($config, "cancelled_flag", "[Cancelled]");
        $this->default_location = Arr::get($config, "default_location", "Church");
        $this->length_in_minutes = Arr::get_integer($config, "length_in_minutes", 60);
        $this->pending_flag = Arr::get($config, "pending_flag", "[tbc]");
        $this->timezone = new DateTimeZone(Arr::get($config, "timezone", "Europe/London"));
    }
}
