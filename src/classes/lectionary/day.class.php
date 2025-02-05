<?php

namespace Obadiah\Lectionary;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;

App::check();

class Day
{
    /**
     * Create Day object.
     *
     * @param string $date                      String (sortable) representation of the date for this day in the lectionary.
     * @param string|null $name                 The name of this day in the lectionary (e.g. 8th after Trinity).
     * @param string|null $colour               The liturgical colour of the day (e.g. White/Gold).
     * @param string|null $collect              The Collect for today.
     * @param string|null $additional_collect   The Additional Collect for today.
     * @param Service[] $services               Array of services on this particular day, sorted by start time.
     * @return void
     */
    public function __construct(
        public readonly string $date,
        public readonly ?string $name,
        public readonly ?string $colour,
        public readonly ?string $collect,
        public readonly ?string $additional_collect,
        public readonly array $services
    ) {}

    /**
     * Get lectionary details for a service at the specified time.
     *
     * @param DateTimeImmutable $dt     Service start time to search for.
     * @return Service|null             Matching lectionary service or null if not found.
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
