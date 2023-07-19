<?php

namespace Feeds\Rota;

use DateTimeImmutable;
use Feeds\App;

App::check();

class Combined_Day
{
    /**
     * Create Combined_Day object.
     *
     * @param DateTimeImmutable $date           DateTime object referencing midnight of the specified day.
     * @param null|string $name                 The name of this day in the lectionary (e.g. 8th after Trinity).
     * @param null|string $colour               The liturgical colour of the day (e.g. White/Gold).
     * @param string $collect                   The Collect for today.
     * @param Combined_Service[] $services      Array of services on this particular day, sorted by start time.
     * @return void
     */
    public function __construct(
        public readonly DateTimeImmutable $date,
        public readonly ?string $name,
        public readonly ?string $colour,
        public readonly string $collect,
        public readonly array $services
    ) {
    }
}
