<?php

namespace Feeds\Lectionary;

use Feeds\Airtable\Airtable;
use Feeds\Base;

class Lectionary
{
    /**
     * The days covered by this lectionary, sorted by date.
     *
     * @var Day[]
     */
    public array $days;

    /**
     * Construct using Lectionary::load().
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Load lectionary from Airtable.
     *
     * @param Base $base                Base object.
     * @return Lectionary               Lectionary object with readings and titles data loaded.
     */
    public static function load_airtable(Base $base) : Lectionary
    {
        // create Lectionary
        $lectionary = new Lectionary();

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
            $day->date = self::get_field($day_fields, "Date");
            $day->name = self::get_field($day_fields, "Name");

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
                $service->time = self::get_field($service_fields, "Time");
                $service->series = self::get_field($service_fields, "Series Title");
                $service->num = self::get_field($service_fields, "Sermon Num");
                $service->title = self::get_field($service_fields, "Sermon Title");
                $service->main_reading = self::get_field($service_fields, "Main Reading");
                $service->additional_reading = self::get_field($service_fields, "Additional Reading");
                $day->services[] = $service;
            }

            // add Day to Lectionary
            $lectionary->days[] = $day;
        }

        // return lectionary
        return $lectionary;
    }

    private static function get_field(array $fields, string $key) : string
    {
        if (array_key_exists($key, $fields)) {
            return $fields[$key];
        }

        return "";
    }
}
