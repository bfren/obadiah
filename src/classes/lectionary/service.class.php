<?php

namespace Obadiah\Lectionary;

use DateInterval;
use Obadiah\App;

App::check();

class Service
{
    /**
     * Create Service object.
     *
     * @param string $time                      The start time of this service.
     * @param DateInterval $length              Length of this service.
     * @param string $name                      The name of this service (e.g. 'Morning Prayer').
     * @param string|null $series               Optional name of the teaching series.
     * @param int|null $num                     Optional 1-based index of this sermon within the teaching series.
     * @param string|null $title                Optional sermon title.
     * @param string|null $main_reading         Optional main reading.
     * @param string|null $additional_reading   Optional additional reading.
     * @param array $psalms                     Optional psalms.
     * @return void
     */
    public function __construct(
        public readonly string $time,
        public readonly DateInterval $length,
        public readonly string $name,
        public readonly ?string $series,
        public readonly ?int $num,
        public readonly ?string $title,
        public readonly ?string $main_reading,
        public readonly ?string $additional_reading,
        public readonly array $psalms
    ) {
    }
}
