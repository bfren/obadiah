<?php

namespace Feeds\Lectionary;

use DateInterval;
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
            "Name",
            "Colour (string)",
            "Collect"
        );
        $days_records = $days->make_request(array("view" => "Feed", "fields" => $days_fields));

        // get services
        $services_fields = array(
            "Date",
            "Time",
            "Length (Minutes)",
            "Name",
            "Series Title",
            "Sermon Num",
            "Sermon Title",
            "Main Reading",
            "Additional Reading",
            "Psalms"
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
                $series[] = Arr::get($service_fields, "Series Title");
                $l_services[] = new Service(
                    time: Arr::get($service_fields, "Time"),
                    length: new DateInterval(sprintf("PT%sM", Arr::get($service_fields, "Length (Minutes)", 60))),
                    name: Arr::get($service_fields, "Name", "Service"),
                    series: Arr::get($service_fields, "Series Title"),
                    num: Arr::get($service_fields, "Sermon Num"),
                    title: Arr::get($service_fields, "Sermon Title"),
                    main_reading: Arr::get($service_fields, "Main Reading"),
                    additional_reading: Arr::get($service_fields, "Additional Reading"),
                    psalms: Arr::map(explode(";", Arr::get($service_fields, "Psalms", "")), "trim")
                );
            }

            // add Day to Lectionary
            $days[] = new Day(
                date: $date,
                name: Arr::get($day_fields, "Name"),
                colour: Arr::get($day_fields, "Colour (string)"),
                collect: Arr::get($day_fields, "Collect"),
                services: $l_services
            );
        }

        // store days
        uasort($days, fn(Day $a, Day $b) => $a->date <=> $b->date);
        $this->days = $days;

        // store series
        asort($series);
        $this->series = array_unique(array_filter($series));
    }

    /**
     * Get day information from the Lectionary for the specified date.
     *
     * @param DateTimeImmutable $dt     Date.
     * @return null|Day                 Lectionary day.
     */
    public function get_day(DateTimeImmutable $dt): ?Day
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

        // return the day
        return $days[0];
    }

    /**
     * Get service information from the Lectionary for the specified date and time.
     *
     * @param DateTimeImmutable $dt     Service date and time.
     * @return null|Service             Lectionary service.
     */
    public function get_service(DateTimeImmutable $dt): ?Service
    {
        if($day = $this->get_day($dt)) {
            return $day->get_service($dt);
        }

        return null;
    }
}
