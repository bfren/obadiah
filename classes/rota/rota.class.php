<?php

namespace Feeds\Rota;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Lectionary\Lectionary;
use Feeds\Rota\Filters\After_Filter;
use Feeds\Rota\Filters\Before_Filter;
use Feeds\Rota\Filters\Day_Filter;
use Feeds\Rota\Filters\Person_Filter;
use Feeds\Rota\Filters\Series_Filter;
use Feeds\Rota\Filters\Start_Filter;

App::check();

class Rota
{
    /**
     * The services covered by this rota.
     *
     * @var Service[]
     */
    public readonly array $services;

    /**
     * All the different people in this rota.
     *
     * @var string[]
     */
    public readonly array $people;

    /**
     * The time the rota csv file was last modified.
     *
     * @var int
     */
    public readonly int $last_modified_timestamp;

    /**
     * Load all files from a rota data directory.
     *
     * @return void
     */
    public function __construct()
    {
        // get csv files from path
        $csv = glob(sprintf("%s/*.csv", C::$dir->rota));

        // read each file into arrays
        $services = array();
        $people = array();
        $last_modified_timestamp = 0;
        foreach ($csv as $file) {
            // store the file modification time
            $last_modified = filemtime($file);
            if ($last_modified > $last_modified_timestamp) {
                $last_modified_timestamp = $last_modified;
            }

            // open the file for reading
            $f = fopen($file, "r");
            if ($f === false) {
                die(sprintf("Unable to open the file: %s.", $file));
            }

            // read each line of the csv file
            $include = false;
            $header_row = array();
            while (($row = fgetcsv($f)) !== false) {
                // include the service
                if ($include && $row[1] != "No service") {
                    // create service
                    $service = new Service($header_row, $row);
                    $services[] = $service;

                    // add people, keeping array unique and sorted alphabetically
                    $people = array_unique(array_merge($people, $service->people));
                    asort($people);
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
        usort($services, fn (Service $a, Service $b) => $a->start->getTimestamp() < $b->start->getTimestamp() ? -1 : 1);

        // check lectionary cache last modified
        $file = Cache::get_cache_file_path(Cache::LECTIONARY);
        $last_modified = filemtime($file);
        if ($last_modified > $last_modified_timestamp) {
            $last_modified_timestamp = $last_modified;
        }

        // set values
        $this->last_modified_timestamp = $last_modified_timestamp;
        $this->services = $services;
        $this->people = $people;
    }

    /**
     * Apply filters and return matching services.
     *
     * @param array                     Filters to apply (usually from the query string).
     * @param Lectionary                Lectionary object.
     * @return Service[]                Services matching the supplied filters.
     */
    public function apply_filters(array $filters, Lectionary $lectionary): array
    {
        // if the filters array is empty, or include=all is set, return all services
        if (!$filters || empty($filters) || Arr::get($filters, "include") == "all") {
            return $this->services;
        }

        // create array to hold matched services
        $services = array();

        // create filter objects
        $person_filter = new Person_Filter();
        $after_filter = new After_Filter();
        $before_filter = new Before_Filter();
        $start_filter = new Start_Filter();
        $day_filter = new Day_Filter();
        $series_filter = new Series_Filter();

        foreach ($this->services as $service) {
            // include by default
            $include = true;

            // apply person filter
            $include = $include && $person_filter->apply($lectionary, $service, Arr::get($filters, "person", ""));

            // apply date filters
            $include = $include && $after_filter->apply($lectionary, $service, Arr::get($filters, "from", ""));
            $include = $include && $before_filter->apply($lectionary, $service, Arr::get($filters, "to", ""));

            // apply start time filter
            $include = $include && $start_filter->apply($lectionary, $service, Arr::get($filters, "start", ""));

            // apply day of the week filter
            $include = $include && $day_filter->apply($lectionary, $service, Arr::get($filters, "day", ""));

            // apply series filter
            $include = $include && $series_filter->apply($lectionary, $service, Arr::get($filters, "series", ""));

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
