<?php

namespace Feeds\Lectionary;

defined("IDX") || die("Nice try.");

class Day
{
    /**
     * String (sortable) representation of the date for this day in the lectionary.
     *
     * @var string
     */
    public string $date;

    /**
     * The name of this day in the lectionary (e.g. 8th after Trinity).
     *
     * @var string
     */
    public string $name;

    /**
     * List of services on this particular day, sorted by start time.
     *
     * @var Service[]
     */
    public array $services;
}
