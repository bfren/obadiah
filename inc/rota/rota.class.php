<?php

namespace Feeds\Rota;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Rota\Filters\After;
use Feeds\Rota\Filters\Before;
use Feeds\Rota\Filters\Day;
use Feeds\Rota\Filters\Person;
use Feeds\Rota\Filters\Start;

defined("IDX") || die("Nice try.");

class Rota
{
    /**
     * The services covered by this rota.
     *
     * @var Service[]
     */
    public array $services = array();

    /**
     * All the different people in this rota.
     *
     * @var string[]
     */
    public array $people = array();

    /**
     * The time the rota csv file was last modified.
     *
     * @var int
     */
    public int $last_modified_timestamp = 0;

    /**
     * Load all files from a rota data directory.
     *
     * @return void
     */
    public function __construct()
    {
        // get csv files from path
        $csv = glob(sprintf("%s/*.csv", C::$dir->rota));

        // read each file
        foreach ($csv as $file) {
            // store the file modification time
            $last_modified = filemtime($file);
            if ($last_modified > $this->last_modified_timestamp) {
                $this->last_modified_timestamp = $last_modified;
            }

            // open the file for reading
            $f = fopen($file, "r");
            if ($f === false) {
                die("Unable to open the file: $file.");
            }

            // read each line of the csv file
            $include = false;
            $header_row = array();

            while (($row = fgetcsv($f)) !== false) {
                // include the service
                if ($include) {
                    $service = new Service($header_row, $row);
                    $this->people = array_unique(array_merge($this->people, $service->people));
                    asort($this->people);
                    $this->services[] = $service;
                }

                // if the first value is 'Date' this is the header row,
                // so the actual data starts with the next row
                if ($row[0] === "Date") {
                    $header_row = $row;
                    $include = true;
                }
            }
        }

        // sort services by timestamp
        usort($this->services, fn ($a, $b) => $a->dt->getTimestamp() < $b->dt->getTimestamp() ? -1 : 1);
    }

    /**
     * Apply filters and return matching services.
     *
     * @param array                     Filters to apply (usually from the query string).
     * @return Service[]                Services matching the supplied filters.
     */
    public function apply_filters(array $filters): array
    {
        // if the filters array is empty, or include=all is set, return all services
        if (!$filters || empty($filters) || Arr::get($filters, "include") == "all") {
            return $this->services;
        }

        // create array to hold matched services
        $services = array();

        // create filter objects
        $person_filter = new Person();
        $after_filter = new After();
        $before_filter = new Before();
        $start_filter = new Start();
        $day_filter = new Day();

        foreach ($this->services as $service) {
            // include by default
            $include = true;

            // apply person filter
            $include = $include && $person_filter->apply($service, Arr::get($filters, "person") ?: "");

            // apply date filters
            $include = $include && $after_filter->apply($service, Arr::get($filters, "from") ?: "");
            $include = $include && $before_filter->apply($service, Arr::get($filters, "to") ?: "");

            // apply start time filter
            $include = $include && $start_filter->apply($service, Arr::get($filters, "start") ?: "");

            // apply day of the week filter
            $include = $include && $day_filter->apply($service, Arr::get($filters, "day") ?: "");

            // include the service if one of the filters has matched
            if ($include) {
                $services[] = $service;
            }
        }

        // if max is set return that number of services
        if ($max = Arr::get($filters, "max")) {
            return array_slice($services, 0, $max, true);
        }

        // return all matched services
        return $services;
    }
}
