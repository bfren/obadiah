<?php

namespace Feeds\Pages\Events;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Calendar\JEvent;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;
use Feeds\Response\Json;

App::check();

class Events
{
    /**
     * Church Suite calendar feed URI.
     */
    private const CALENDAR_HREF = "https://%s.churchsuite.com/embed/calendar/json?%s";

    /**
     * GET: /events/json
     *
     * @return Json
     */
    public function json_get(): Json
    {
        // load events
        $events = Cache::get_events(fn () => $this->get_events());

        // return JSON action
        return new Json($events);
    }

    /**
     * Load events from the Church Suite calendar feed.
     *
     * @return mixed                    Array of events or curl error.
     */
    private function get_events(): array
    {
        // get query options
        $query = array(
            "date_start" => Request::$get->string("start"),
            "date_end" => Request::$get->string("end")
        );

        // setup curl
        $url = sprintf(self::CALENDAR_HREF, C::$general->church_suite_org, http_build_query($query));
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // get calendar JSON
        $json = curl_exec($ch);
        if (curl_errno($ch) != 0) {
            return array();
        }

        // decode JSON
        $calendar = json_decode($json, true);
        if (!is_array($calendar)) {
            return array();
        }

        // build events array
        $events = array();
        foreach ($calendar as $event) {
            $events[] = new JEvent(
                id: Arr::get($event, "id"),
                start: Arr::get($event, "datetime_start"),
                end: Arr::get($event, "datetime_end"),
                title: Arr::get($event, "name"),
                description: Arr::get($event, "description")
            );
        }

        // return events
        return $events;
    }
}
