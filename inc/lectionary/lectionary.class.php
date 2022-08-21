<?php

namespace Feeds\Lectionary;

use DateTimeImmutable;
use Feeds\Airtable\Airtable;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

defined("IDX") || die("Nice try.");

class Lectionary
{
    /**
     * The days covered by this lectionary, sorted by date.
     *
     * @var Day[]
     */
    public array $days = array();

    /**
     * The series covered by this lectionary, sorted alphabetically.
     *
     * @var string[]
     */
    public array $series = array();

    /**
     * Load lectionary from Airtable.
     *
     * @return void
     */
    public function __construct()
    {
        // create Airtable loaders
        $days = new Airtable("Day");
        $services = new Airtable("Service");

        // get days
        $days_fields = array(
            "Date",
            "Name"
        );
        $days_records = $days->make_request(array("view" => "Feed", "fields" => $days_fields));

        // get services
        $services_fields = array(
            "Date",
            "Time",
            "Series Title",
            "Sermon Num",
            "Sermon Title",
            "Main Reading",
            "Additional Reading"
        );
        $services_records = $services->make_request(array("view" => "Feed", "fields" => $services_fields));

        // add days and services
        $series = array();
        foreach ($days_records as $day_record) {
            // create Day
            $day = new Day();
            $day_fields = $day_record["fields"];
            $day->date = Arr::get($day_fields, "Date");
            $day->name = Arr::get($day_fields, "Name");

            // if date is not set, continue
            if (!$day->date) {
                continue;
            }

            // get Services for Day
            $day_services = array_filter($services_records, function ($v, $k) use ($day) {
                return $v["fields"]["Date"] === $day->date;
            }, ARRAY_FILTER_USE_BOTH);

            // if there are no services, continue
            if (empty($day_services)) {
                continue;
            }

            // add Services to Day
            foreach ($day_services as $service_record) {
                $service = new Service();
                $service_fields = $service_record["fields"];
                $service->time = Arr::get($service_fields, "Time");
                $service->series = Arr::get($service_fields, "Series Title");
                $service->num = Arr::get($service_fields, "Sermon Num");
                $service->title = Arr::get($service_fields, "Sermon Title");
                $service->main_reading = Arr::get($service_fields, "Main Reading");
                $service->additional_reading = Arr::get($service_fields, "Additional Reading");
                $day->services[] = $service;
                $series[] = $service->series;
            }

            // add Day to Lectionary
            $this->days[] = $day;
        }

        // store series
        $this->series = array_unique(array_filter($series));
        asort($this->series);
    }

    /**
     * Get service information from the Lectionary for the specified date and time.
     *
     * @param DateTimeImmutable $dt     Service date and time.
     * @return null|Service             Lectionary service.
     */
    public function get_service(DateTimeImmutable $dt): ?Service
    {
        // get the Lectionary day
        $date = $dt->format(C::$formats->sortable_date);
        $days = Arr::match($this->days, function (Day $day) use ($date) {
            return $day->date == $date;
        });

        // there should be precisely one day - if not, return null
        if (count($days) != 1) {
            return null;
        }

        // get the service at the specified time on this day
        return $days[0]->get_service($dt);
    }
}
