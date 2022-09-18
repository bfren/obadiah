<?php

namespace Feeds\Refresh;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;

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
        $today = new DateTimeImmutable("now", C::$general->timezone);
        //$this->today = new Day($today, null, array());
        $period = new DatePeriod(
            $today->modify("-1 week"),
            new DateInterval("P1D"),
            $today->modify("+2 days")
        );

        // get data from caches
        $bible = Cache::get_bible_plan();
        $prayer = Cache::get_prayer_calendar();

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
                readings: $bible->get_day($value),
                people: $prayer->get_day($value)
            );

            // set today
            //if($value == $today) {
            if (!isset($this->today)) {
                $this->today = $day;
            }

            // add to days array
            $days[] = $day;
        }

        // store days
        $this->days = $days;
    }
}
