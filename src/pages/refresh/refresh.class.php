<?php

namespace Feeds\Pages\Refresh;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Calendar\JEvent;
use Feeds\Calendar\VCal;
use Feeds\Calendar\VEvent;
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
            $events[] = new VEvent(
                uid: VEvent::get_uid(time()),
                start: $day->date,
                end: $day->date,
                summary: $day->get_summary(),
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
            $events[] = new JEvent(
                id: JEvent::get_id(time()),
                start: $day->date->format(C::$formats->json_datetime),
                end: $day->date->format(C::$formats->json_datetime),
                title: $day->get_summary(),
                description: $day->get_description("\n"),
                is_all_day: true
            );
        }

        // return Json action
        return new Json($events);
    }
}
