<?php

namespace Feeds\Rota;

use DateTimeImmutable;

class Combined_Service
{
    /**
     * Service start.
     *
     * @var DateTimeImmutable
     */
    public DateTimeImmutable $start;

    /**
     * Service end.
     *
     * @var DateTimeImmutable
     */
    public DateTimeImmutable $end;

    /**
     * Service start time (e.g. 10:30).
     *
     * @var string
     */
    public string $time;

    /**
     * Service name (e.g. 'Morning Prayer').
     *
     * @var string
     */
    public string $name;

    /**
     * Optional series title.
     *
     * @var null|string
     */
    public ?string $series_title;

    /**
     * Optional sermon number.
     *
     * @var null|string
     */
    public ?string $sermon_num;

    /**
     * Optional sermon title.
     *
     * @var null|string
     */
    public ?string $sermon_title;

    /**
     * Optional main reading.
     *
     * @var null|string
     */
    public ?string $main_reading;

    /**
     * Optional additional reading.
     *
     * @var null|string
     */
    public ?string $additional_reading;

    /**
     * Roles from the rota.
     *
     * @var array
     */
    public array $roles = array();
}
