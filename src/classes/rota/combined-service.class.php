<?php

namespace Obadiah\Rota;

use DateTimeImmutable;
use Obadiah\App;

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
     * @param string|null $series_title         Optional name of the teaching series.
     * @param int|null $sermon_num              Optional 1-based index of this sermon within the teaching series.
     * @param string|null $sermon_title         Optional sermon title.
     * @param string|null $main_reading         Optional main reading.
     * @param string|null $additional_reading   Optional additional reading.
     * @param string[] $psalms                  Optional psalms.
     * @param string|null $guest_speaker        Optional guest speaker.
     * @param Service_Ministry[] $ministries    Ministries from the rota.
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
        public readonly array $psalms,
        public readonly ?string $guest_speaker,
        public readonly array $ministries
    ) {}
}
