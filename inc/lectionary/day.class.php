<?php

namespace Feeds\Lectionary;

use Feeds\Config\Config as C;

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
     * @var null|string
     */
    public ?string $name;

    /**
     * List of services on this particular day, sorted by start time.
     *
     * @var Service[]
     */
    public array $services;

    /**
     * Get lectionary details for a service at the specified time.
     *
     * @param int $timestamp            Service timestamp to search for.
     * @return null|Service             Matching lectionary service or null if not found.
     */
    public function get_service(int $timestamp): ?Service
    {
        // get formatted time value
        $time = date(C::$formats->display_time, $timestamp);

        // search for a service at the specified time
        $services = array_values(array_filter($this->services, function ($service) use ($time) {
            return $service->time == $time;
        }));

        // if no services (or multiple services) were found, return null
        if (!$services || count($services) != 1) {
            return null;
        }

        // there should only be one service in the array so return it
        return $services[0];
    }
}
