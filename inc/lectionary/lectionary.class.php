<?php

namespace Feeds\Lectionary;

use Feeds\Airtable\Airtable;
use Feeds\Base;
use Feeds\Helpers\Arr;

class Lectionary
{
    /**
     * The days covered by this lectionary, sorted by date.
     *
     * @var Day[]
     */
    public array $days;

    /**
     * Load lectionary from Airtable.
     *
     * @param Base $base                Base object.
     * @return void
     */
    public function __construct(Base $base)
    {
        // create Airtable loaders
        $days = new Airtable($base, "Day");
        $services = new Airtable($base, "Service");

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
            $day_services = array_filter($services_records, function($v, $k) use ($day) {
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
            }

            // add Day to Lectionary
            $this->days[] = $day;
        }
    }
}
