<?php

namespace Obadiah\Lectionary;

use DateInterval;
use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Baserow\Baserow;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;

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
     * Load lectionary and services from Baserow.
     *
     * @return void
     */
    public function __construct()
    {
        // create Baserow loaders
        $day_table = Baserow::Day();
        $service_table = Baserow::Service();

        // get days
        $day = array(
            "Date",
            "Name",
            "Colour",
            "Collect"
        );
        $day_results = $day_table->get(array("include" => join(",", $day)));

        // get services
        $service = array(
            "Date",
            "Time",
            "Length",
            "Service Name",
            "Series Title",
            "Num",
            "Title",
            "Main Reading",
            "Additional Reading",
            "Psalms"
        );
        $service_results = $service_table->get(array("include" => join(",", $service)));

        // add days and services
        $days = [];
        $series = [];
        foreach ($day_results as $day) {
            // check date - if it is not set, continue
            $date = Arr::get($day, "Date", "");
            if (!$date) {
                continue;
            }

            // get Services for Day
            $day_services = array_filter($service_results, function (array $v, int $k) use ($date) {
                return $v["Date"] === $date;
            }, ARRAY_FILTER_USE_BOTH);

            // if there are no services, continue
            if (empty($day_services)) {
                continue;
            }

            // add Services to Day
            $l_services = [];
            foreach ($day_services as $service) {
                $series[] = Arr::get($service, "Series Title");
                $l_services[] = new Service(
                    time: Arr::get_required($service, "Time"),
                    length: new DateInterval(sprintf("PT%sM", Arr::get($service, "Length", 60))),
                    name: Arr::get_required($service, "Service Name"),
                    series: Arr::get($service, "Series Title"),
                    num: Arr::get($service, "Num"),
                    title: Arr::get($service, "Title"),
                    main_reading: Arr::get($service, "Main Reading"),
                    additional_reading: Arr::get($service, "Additional Reading"),
                    psalms: Arr::map(explode(";", Arr::get($service, "Psalms", "")), "trim")
                );
            }

            // add Day to Lectionary
            $days[] = new Day(
                date: $date,
                name: Arr::get($day, "Name"),
                colour: Arr::get($day, "Colour"),
                collect: Arr::get($day, "Collect"),
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
     * Get the Collect for the specified day - or the previous Sunday if there isn't one.
     *
     * @param DateTimeImmutable $dt     Date.
     * @return string|null              Collect or null if not found.
     */
    public function get_collect(DateTimeImmutable $dt): ?string
    {
        // if this is a Lectionary day, return its collect
        $day = $this->get_day($dt);
        if ($day !== null) {
            return $day->collect;
        }

        // get the collect for the previous Sunday
        $previous_sunday = $this->get_day($dt->modify("previous Sunday"));
        if ($previous_sunday !== null) {
            return $previous_sunday->collect;
        }

        // return nothing
        return null;
    }

    /**
     * Get day information from the Lectionary for the specified date.
     *
     * @param DateTimeImmutable $dt     Date.
     * @return Day|null                 Lectionary day.
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
     * @return Service|null             Lectionary service.
     */
    public function get_service(DateTimeImmutable $dt): ?Service
    {
        if ($day = $this->get_day($dt)) {
            return $day->get_service($dt);
        }

        return null;
    }
}
