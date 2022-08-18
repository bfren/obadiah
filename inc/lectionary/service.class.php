<?php

namespace Feeds\Lectionary;

defined("IDX") || die("Nice try.");

class Service
{
    /**
     * The start time of this service.
     *
     * @var string
     */
    public string $time;

    /**
     * The name of the teaching series.
     *
     * @var null|string
     */
    public ?string $series;

    /**
     * The 1-based index of this sermon within the teaching series.
     *
     * @var null|int
     */
    public ?int $num;

    /**
     * The sermon title.
     *
     * @var null|string
     */
    public ?string $title;

    /**
     * The main reading.
     *
     * @var null|string
     */
    public ?string $main_reading;

    /**
     * An optional additional reading.
     *
     * @var null|string
     */
    public ?string $additional_reading;
}
