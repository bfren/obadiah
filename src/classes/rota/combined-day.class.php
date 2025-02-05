<?php

namespace Obadiah\Rota;

use DateTimeImmutable;
use Obadiah\App;

App::check();

class Combined_Day
{
    /**
     * Create Combined_Day object.
     *
     * @param DateTimeImmutable $date           DateTime object referencing midnight of the specified day.
     * @param string|null $name                 The name of this day in the lectionary (e.g. 8th after Trinity).
     * @param string|null $colour               The liturgical colour of the day (e.g. White/Gold).
     * @param string $collect                   The Collect for today.
     * @param string $additional_collect        The Additional Collect for today.
     * @param Combined_Service[] $services      Array of services on this particular day, sorted by start time.
     * @return void
     */
    public function __construct(
        public readonly DateTimeImmutable $date,
        public readonly ?string $name,
        public readonly ?string $colour,
        public readonly string $collect,
        public readonly string $additional_collect,
        public readonly array $services
    ) {}
}
