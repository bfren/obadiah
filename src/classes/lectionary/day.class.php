<?php

namespace Feeds\Lectionary;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

App::check();

class Day
{
    /**
     * Create Day object.
     *
     * @param string $date              String (sortable) representation of the date for this day in the lectionary.
     * @param null|string $name         The name of this day in the lectionary (e.g. 8th after Trinity).
     * @param null|string $collect      The Collect for today.
     * @param Service[] $services       List of services on this particular day, sorted by start time.
     * @return void
     */
    public function __construct(
        public readonly string $date,
        public readonly ?string $name,
        public readonly ?string $collect,
        public readonly array $services
    ) {
    }

    /**
     * Get lectionary details for a service at the specified time.
     *
     * @param DateTimeImmutable $dt     Service start time to search for.
     * @return null|Service             Matching lectionary service or null if not found.
     */
    public function get_service(DateTimeImmutable $dt): ?Service
    {
        // get formatted time value
        $time = $dt->format(C::$formats->display_time);

        // search for a service at the specified time
        $services = Arr::match($this->services, function (Service $service) use ($time) {
            return $service->time == $time;
        });

        // if no services (or multiple services) were found, return null
        if (!$services || count($services) != 1) {
            return null;
        }

        // there should only be one service in the array so return it
        return $services[0];
    }
}
