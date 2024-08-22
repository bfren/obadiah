<?php

namespace Obadiah\Refresh;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Prayer\Prayer_Calendar;

App::check();

class Refresh
{
    /**
     * Array of days to display.
     *
     * @var Day[]
     */
    public readonly array $days;

    /**
     * Today's refresh.
     *
     * @var Day
     */
    public readonly Day $today;

    /**
     * Create Refresh calendar.
     *
     * @return void
     */
    public function __construct()
    {
        // generate time period for the calendar
        $today = new DateTimeImmutable("now", C::$events->timezone);

        $period = new DatePeriod(
            $today->modify(sprintf("-%d days", C::$refresh->days_before)),
            new DateInterval("P1D"),
            $today->modify(sprintf("+%d days", C::$refresh->days_after + 1))
        );

        // get data from caches
        $bible = Cache::get_bible_plan();

        // get readings and people for each day
        $days = array();
        foreach ($period as $value) {
            // skip Sundays
            if ($value->format("N") == "7") {
                continue;
            }

            // create day object
            $day = new Day(
                date: $value,
                people: Prayer_Calendar::get_day($value, Prayer_Calendar::RETURN_OBJECT),
                readings: $bible->get_day($value)
            );

            // set today
            if ($value == $today) {
                $this->today = $day;
            }

            // add to days array
            $days[] = $day;
        }

        // store days
        $this->days = $days;

        // if today has not been set, create an empty one for today
        if (!isset($this->today)) {
            $this->today = new Day($today, people: array(), readings: null);
        }
    }
}
