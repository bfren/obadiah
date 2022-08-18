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
     * @var string
     */
    public string $series;

    /**
     * The 1-based index of this sermon within the teaching series.
     *
     * @var int
     */
    public int $num;

    /**
     * The sermon title.
     *
     * @var string
     */
    public string $title;

    /**
     * The main reading.
     *
     * @var string
     */
    public string $main_reading;

    /**
     * An optional additional reading.
     *
     * @var string
     */
    public string $additional_reading;
}
