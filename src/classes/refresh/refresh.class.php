<?php

namespace Obadiah\Refresh;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;
use Obadiah\Pages\Events\Events;
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
        $events = Events::get_events(array(
            "start" => $period->start->format(C::$formats->sortable_date),
            "end" => $period->end->format(C::$formats->sortable_date)
        ));

        // get readings and people for each day
        $days = [];
        $today_value = null;
        foreach ($period as $value) {
            // skip Sundays
            if ($value->format("N") == "7") {
                continue;
            }

            // create day object
            $immutable = DateTimeImmutable::createFromInterface($value);
            $date = $immutable->format(C::$formats->sortable_date);
            $date_events = Arr::match($events, fn ($e) => $e->start->format(C::$formats->sortable_date) == $date);
            $day = new Day(
                date: $immutable,
                people: Prayer_Calendar::get_day($immutable, Prayer_Calendar::RETURN_OBJECT),
                events: $date_events,
                readings: $bible->get_day($immutable)
            );

            // set today
            if ($immutable == $today) {
                $today_value = $day;
            }

            // add to days array
            $days[] = $day;
        }

        // store days
        $this->days = $days;

        // if today has not been set, create an empty one for today
        $this->today = $today_value ?: new Day($today, people: [], events: [], readings: null);
    }
}
