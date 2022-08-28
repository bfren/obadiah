<?php

namespace Feeds\Rota;

use DateTimeImmutable;
use Feeds\App;

App::check();

class Combined_Service
{
    /**
     * Create Combined_Service object.
     *
     * @param DateTimeImmutable $start          Service start.
     * @param DateTimeImmutable $end            Service end.
     * @param string $time                      Service start time (e.g. 10:30).
     * @param string $name                      Service name (e.g. 'Morning Prayer').
     * @param null|string $series_title         Optional name of the teaching series.
     * @param null|int $sermon_num              Optional 1-based index of this sermon within the teaching series.
     * @param null|string $sermon_title         Optional sermon title.
     * @param null|string $main_reading         Optional main reading.
     * @param null|string $additional_reading   Optional additional reading.
     * @param Service_Role[] $roles             Roles from the rota.
     * @param null|string $collect              Optional Collect.
     * @return void
     */
    public function __construct(
        public readonly DateTimeImmutable $start,
        public readonly DateTimeImmutable $end,
        public readonly string $time,
        public readonly string $name,
        public readonly ?string $series_title,
        public readonly ?int $sermon_num,
        public readonly ?string $sermon_title,
        public readonly ?string $main_reading,
        public readonly ?string $additional_reading,
        public readonly array $roles,
        public readonly ?string $collect
    ) {
    }
}
