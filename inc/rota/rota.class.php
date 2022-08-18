<?php

namespace Feeds\Rota;

use Feeds\Base;
use Feeds\Helpers\Arr;
use Feeds\Rota\Filters\After;
use Feeds\Rota\Filters\Before;
use Feeds\Rota\Filters\Day;
use Feeds\Rota\Filters\Person;
use Feeds\Rota\Filters\Start;

class Rota
{
    /**
     * The services covered by this rota.
     *
     * @var Service[]
     */
    public array $services;

    /**
     * Load all files from a rota data directory.
     *
     * @param Base $base                Base object.
     * @return void
     */
    public function __construct(Base $base)
    {
        // get csv files from path
        $csv = glob($base->dir_rota . "/*.csv");

        // read each file
        foreach ($csv as $file) {

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
                    $this->services[] = new Service($header_row, $row);
                }

                // if the first value is 'Date' this is the header row,
                // so the actual data starts with the next row
                if ($row[0] === "Date") {
                    $header_row = $row;
                    $include = true;
                }
            }
        }
    }

    /**
     * Apply filters and return matching services.
     *
     * @param array                     Filters to apply (usually from the query string).
     * @return Service[]                Services matching the supplied filters.
     */
    public function apply_filters(array $filters) : array
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
            $include = $include && $person_filter->apply($service, Arr::get($filters, "person"));

            // apply date filters
            $include = $include && $after_filter->apply($service, Arr::get($filters, "from"));
            $include = $include && $before_filter->apply($service, Arr::get($filters, "to"));

            // apply start time filter
            $include = $include && $start_filter->apply($service, Arr::get($filters, "start"));

            // apply day of the week filter
            $include = $include && $day_filter->apply($service, Arr::get($filters, "day"));

            // include the service if one of the filters has matched
            if ($include) {
                $services[] = $service;
            }
        }

        // return all matched services
        return $services;
    }
}
