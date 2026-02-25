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
use Obadiah\Router\Endpoint;

App::check();

class Events extends Endpoint
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
        $query = self::get_query(Request::$get->all());
        $events = Cache::get_events($query, fn(string $q) => self::get_events($q));

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
        $events = Cache::get_events(self::get_query(Request::$get->all()), fn(string $q) => self::get_events($q));

        // return JSON action
        return new Json($events);
    }

    /**
     * Get Church Suite compatible query options.
     *
     * @param array<string, mixed> $values          Query values.
     * @return string                               URL-encoded query (using http_build_query()).
     */
    private static function get_query(array $values): string
    {
        // get query options
        $query = array(
            "category" => Arr::get_integer($values, "cat"),
            "date_start" => Arr::get($values, "start"),
            "date_end" => Arr::get($values, "end"),
            "q" => Arr::get($values, "q"),
        );

        // return encoded query
        return http_build_query($query);
    }

    /**
     * Execute a CURL request with retry logic and exponential backoff.
     *
     * @param resource $handle                      CURL handle.
     * @param int $max_retries                      Maximum number of retries.
     * @return string|bool                          Response string or false on failure.
     */
    private static function execute_with_retry($handle, int $max_retries = 3): string|bool
    {
        for ($attempt = 0; $attempt <= $max_retries; $attempt++) {
            $response = curl_exec($handle);
            $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            // Success on 2xx responses
            if (is_string($response) && $http_code >= 200 && $http_code < 300) {
                return $response;
            }

            // Don't retry on 4xx errors (client errors)
            if ($http_code >= 400 && $http_code < 500) {
                return $response;
            }

            // Retry on network errors or 5xx errors
            if ($attempt < $max_retries) {
                $wait_time = pow(2, $attempt); // 1s, 2s, 4s, 8s
                _l("Retry attempt %d for Church Suite calendar (waiting %ds).", $attempt + 1, $wait_time);
                usleep($wait_time * 1000000); // Convert to microseconds
            }
        }

        return false;
    }

    /**
     * Get events from Church Suite matching the query.
     *
     * @param array<string, mixed>|string $query    Array of query values, or URL-encoded query (e.g. using http_build_query()).
     * @return Event[]                              Array of matching events.
     */
    public static function get_events(array|string $query): array
    {
        // build query string
        $query_values = is_array($query) ? self::get_query($query) : $query;

        // create curl request
        $url = sprintf(self::CALENDAR_HREF, C::$churchsuite->org, $query_values);
        $handle = curl_init($url);
        if ($handle === false) {
            _l("Unable to create cURL request for %s.", $url);
            return [];
        }

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($handle, CURLOPT_TCP_KEEPALIVE, 1);

        // get calendar JSON with retry logic
        $json = self::execute_with_retry($handle);
        if (!is_string($json)) {
            _l(curl_error($handle));
            curl_close($handle);
            return [];
        }
        curl_close($handle);

        // decode JSON
        $calendar = json_decode($json, true);
        if (!is_array($calendar)) {
            return [];
        }

        // build events array
        $events = [];
        foreach ($calendar as $event) {
            // get title
            $title = Arr::get($event, "name", "Unknown");

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
            if (($address = Arr::get($location_data, "address")) != null) {
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

        // sort events by start time
        usort($events, fn($a, $b) => ($a->start < $b->start) ? -1 : 1);

        // return events
        return $events;
    }
}
