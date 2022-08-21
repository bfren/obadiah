<?php

namespace Feeds\Lectionary;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Airtable\Airtable;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

App::check();

class Lectionary
{
    /**
     * The days covered by this lectionary, sorted by date.
     *
     * @var Day[]
     */
    public readonly array $days;

    /**
     * The series covered by this lectionary, sorted alphabetically.
     *
     * @var string[]
     */
    public readonly array $series;

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
        $days = array();
        $series = array();
        foreach ($days_records as $day_record) {
            // get fields
            $day_fields = $day_record["fields"];

            // check date - if it is not set, continue
            $date = Arr::get($day_fields, "Date");
            if (!$date) {
                continue;
            }

            // get Services for Day
            $day_services = array_filter($services_records, function (array $v, int $k) use ($date) {
                return $v["fields"]["Date"] === $date;
            }, ARRAY_FILTER_USE_BOTH);

            // if there are no services, continue
            if (empty($day_services)) {
                continue;
            }

            // add Services to Day
            $l_services = array();
            foreach ($day_services as $service_record) {
                $service_fields = $service_record["fields"];
                $l_services[] = new Service(
                    Arr::get($service_fields, "Time"),
                    Arr::get($service_fields, "Series Title"),
                    Arr::get($service_fields, "Sermon Num"),
                    Arr::get($service_fields, "Sermon Title"),
                    Arr::get($service_fields, "Main Reading"),
                    Arr::get($service_fields, "Additional Reading")
                );
                $series[] = Arr::get($service_fields, "Series Title");
            }

            // add Day to Lectionary
            $days[] = new Day($date, Arr::get($day_fields, "Name"), $l_services);
        }

        // store arrays
        $this->days = $days;
        asort($series);
        $this->series = array_unique(array_filter($series));
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