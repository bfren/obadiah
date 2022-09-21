<?php

namespace Feeds\Pages\Services;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Calendar\Event;
use Feeds\Calendar\VCal;
use Feeds\Config\Config as C;
use Feeds\Pages\Rota\Rota;
use Feeds\Response\ICalendar;
use Feeds\Response\Json;
use Feeds\Rota\Builder;

App::check();

class Services
{
    /**
     * GET: /services/ics
     *
     * @return ICalendar
     */
    public function ics_get(): ICalendar
    {
        // get combined rota
        $rota = Rota::build_combined_rota();

        // build events array
        $events = array();
        foreach ($rota as $day) {
            foreach ($day->services as $service) {
                $events[] = new Event(
                    uid: Event::create_uid(Cache::get_rota_last_modified()),
                    start: $service->start,
                    end: $service->end,
                    title: Builder::get_summary($service),
                    location: C::$events->default_location,
                    description: Builder::get_description($day, $service, false)
                );
            }
        }

        // create calendar
        $vcal = new VCal($events, Cache::get_rota_last_modified());

        // return ICalendar action
        return new ICalendar("services", $vcal);
    }

    /**
     * GET: /services/json
     *
     * @return Json
     */
    public function json_get(): Json
    {
        // get combined rota
        $rota = Rota::build_combined_rota();

        // build events array
        $events = array();
        foreach ($rota as $day) {
            foreach ($day->services as $service) {
                $events[] = new Event(
                    uid: Event::create_uid(Cache::get_rota_last_modified()),
                    start: $service->start,
                    end: $service->end,
                    title: Builder::get_summary($service),
                    location: C::$events->default_location,
                    description: Builder::get_description($day, $service, false, "\n")
                );
            }
        }

        // return Json action
        return new Json($events, last_modified: Cache::get_rota_last_modified());
    }
}
