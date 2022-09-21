<?php

namespace Feeds\Pages\Refresh;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Calendar\Event;
use Feeds\Calendar\VCal;
use Feeds\Config\Config as C;
use Feeds\Response\ICalendar;
use Feeds\Response\Json;
use Feeds\Response\View;

App::check();

class Refresh
{
    /**
     * GET: /refresh
     *
     * @return View
     */
    public function index_get(): View
    {
        // get calendar
        $refresh = Cache::get_refresh();

        // return view
        return new View("refresh", model: new Index_Model(
            today: $refresh->today
        ));
    }

    /**
     * GET: /refresh/ics
     *
     * @return ICalendar
     */
    public function ics_get(): ICalendar
    {
        // get calendar
        $refresh = Cache::get_refresh();

        // build events array
        $events = array();
        foreach ($refresh->days as $day) {
            $events[] = new Event(
                uid: Event::create_uid(Cache::get_refresh_last_modified()),
                start: $day->date,
                end: $day->date,
                title: $day->get_summary(),
                location: C::$events->default_location,
                description: $day->get_description(),
                is_all_day: true
            );
        }

        // create calendar
        $vcal = new VCal($events, time());

        // return ICalendar action
        return new ICalendar("refresh", $vcal);
    }

    /**
     * GET: /refresh/json
     *
     * @return Json
     */
    public function json_get(): Json
    {
        // get calendar
        $refresh = Cache::get_refresh();

        // build events array
        $events = array();
        foreach ($refresh->days as $day) {
            $events[] = new Event(
                uid: Event::create_uid(Cache::get_refresh_last_modified()),
                start: $day->date,
                end: $day->date,
                title: $day->get_summary(),
                location: C::$events->default_location,
                description: $day->get_description("\n"),
                is_all_day: true
            );
        }

        // return Json action
        return new Json($events);
    }
}
