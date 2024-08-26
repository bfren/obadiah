<?php

namespace Obadiah\Pages\Events;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Cache\Cache;
use Obadiah\Calendar\Event;
use Obadiah\Calendar\VCal;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;
use Obadiah\Request\Request;
use Obadiah\Response\ICalendar;
use Obadiah\Response\Json;

App::check();

class Events
{
    /**
     * Church Suite calendar feed URI.
     */
    private const CALENDAR_HREF = "https://%s.churchsuite.com/embed/calendar/json?%s";

    /**
     * GET: /events/ics
     *
     * @return ICalendar
     */
    public function ics_get(): ICalendar
    {
        // get events
        $query = self::get_query();
        $events = Cache::get_events($query, fn (string $q) => self::get_events($q));

        // create calendar
        $vcal = new VCal($events, Cache::get_events_last_modified($query));

        // return ICalendar action
        return new ICalendar("events", $vcal);
    }

    /**
     * GET: /events/json
     *
     * @return Json
     */
    public function json_get(): Json
    {
        // get events
        $events = Cache::get_events(self::get_query(), fn (string $q) => self::get_events($q));

        // return JSON action
        return new Json($events);
    }

    /**
     * Get Church Suite compatible query options.
     *
     * @return string                   URL-encoded query (using http_build_query()).
     */
    private static function get_query():string
    {
        // get query options
        $query = array(
            "category" => Request::$get->int("cat"),
            "date_start" => Request::$get->string("start"),
            "date_end" => Request::$get->string("end"),
            "q" => Request::$get->string("q"),
        );

        // return encoded query
        return http_build_query($query);
    }

    /**
     * Get events from Church Suite matching the query.
     *
     * @param string $query             URL-encoded query (e.g. using http_build_query()).
     * @return Event[]                  Array of matching events.
     */
    public static function get_events(string $query): array
    {
        // setup curl
        $url = sprintf(self::CALENDAR_HREF, C::$churchsuite->org, $query);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // get calendar JSON
        $json = curl_exec($ch);
        if (curl_errno($ch) != 0) {
            return [];
        }

        // decode JSON
        $calendar = json_decode($json, true);
        if (!is_array($calendar)) {
            return [];
        }

        // build events array
        $events = [];
        foreach ($calendar as $event) {
            // get title
            $title = Arr::get($event, "name");

            // get status - can be 'confirmed', 'pending' or 'cancelled'
            // add flag to title if necessary
            $status = Arr::get($event, "status");
            if ($status == "cancelled") {
                $title = sprintf("%s %s", C::$events->cancelled_flag, $title);
            } else if ($status == "pending") {
                $title = sprintf("%s %s", C::$events->pending_flag, $title);
            }

            // get location
            $location_data = Arr::get($event, "location", []);
            if(($address = Arr::get($location_data, "address")) !== null) {
                $location = $address;
            } else {
                $location = Arr::get($location_data, "name", C::$events->default_location);
            }

            // build and event to the array
            $events[] = new Event(
                uid: Arr::get_required($event, "id"),
                start: new DateTimeImmutable(Arr::get_required($event, "datetime_start"), C::$events->timezone),
                end: new DateTimeImmutable(Arr::get_required($event, "datetime_end"), C::$events->timezone),
                title: $title,
                location: $location,
                description: Arr::get($event, "description")
            );
        }

        // return events
        return $events;
    }
}
