<?php

namespace Obadiah\Calendar;

use DateTimeZone;
use Obadiah\App;
use Obadiah\Calendar\TZ\Europe_London;
use Obadiah\Config\Config as C;

App::check();

class VCal
{
    /**
     * Holds each line of the calendar output.
     *
     * @var string[]
     */
    private array $lines = [];

    /**
     * Create VCal object.
     *
     * @param Event[] $events           Array of events in this calendar.
     * @param int $last_modified        Timestamp when the calendar was last modified.
     * @return void
     */
    public function __construct(
        public readonly array $events,
        public readonly int $last_modified
    ) {}

    /**
     * Get ICal timezone definition, or null if not supported.
     *
     * @param DateTimeZone $timezone    Config timezone.
     * @return Timezone|null            ICal timezone.
     */
    private static function get_ical_timezone(DateTimeZone $timezone): ?Timezone
    {
        $name = $timezone->getName();
        return match ($name) {
            "Europe/London" => new Europe_London(),
            default => null
        };
    }

    /**
     * Generate output, ensure no lines are longer than 75 characters, and print to output.
     *
     * @return void
     */
    public function print_output(): void
    {
        // begin calendar definition
        $this->lines[] = "BEGIN:VCALENDAR";
        $this->lines[] = "VERSION:2.0";
        $this->lines[] = sprintf("PRODID:-//%s//NONSGML//EN", C::$general->church_domain);
        $this->lines[] = "CALSCALE:GREGORIAN";
        $this->lines[] = "X-PUBLISHED-TTL:PT1H";

        // add timezone definition
        $tz = self::get_ical_timezone(C::$events->timezone)?->get_definition();
        if ($tz) {
            $this->lines = array_merge($this->lines, $tz);
        }

        // add each event to $lines
        foreach ($this->events as $event) {
            $this->add_event($event);
        }

        // end calendar
        $this->lines[] = "END:VCALENDAR";

        // holds the text output of the calendar
        $ics = "";

        // ensure no lines are longer than $max characters
        $max = 74;
        foreach ($this->lines as $line) {
            // loop until each line is under $max characters
            $str = $line;
            $folded = "";
            while (true) {
                // if the remaining string is $max characters or under, add and break
                if (strlen($str) <= $max) {
                    $folded .= $str;
                    break;
                }

                // get the first $max characters
                $folded .= sprintf("%s\r\n ", substr($str, 0, $max));

                // remove the first $max characters and go again
                $str = substr($str, $max);
            }

            // add the folded string to the ics string
            $ics .= sprintf("%s\r\n", $folded);
        }

        // output text
        print_r($ics);
    }

    /**
     * Add event to $lines array.
     *
     * @param Event $event              The event to add.
     * @return void
     */
    private function add_event(Event $event): void
    {
        $tzid = $event->start->getTimezone()->getName();

        $this->lines[] = "BEGIN:VEVENT";
        $this->lines[] = sprintf("UID:%s", $event->uid);

        if ($event->is_all_day) {
            $this->lines[] = sprintf("DTSTART;TZID=%s;VALUE=DATE:%s", $tzid, $event->start->format(C::$formats->ics_date));
            $this->lines[] = "TRANSP:TRANSPARENT";
        } else {
            $this->lines[] = sprintf("DTSTART;TZID=%s:%s", $tzid, $event->start->format(C::$formats->ics_datetime));
            $this->lines[] = sprintf("DTEND;TZID=%s:%s", $tzid, $event->end->format(C::$formats->ics_datetime));
        }

        $this->lines[] = sprintf("SUMMARY:%s", $event->title);
        $this->lines[] = sprintf("LOCATION:%s", $event->location);
        $this->lines[] = sprintf("DESCRIPTION:%s", $event->description);
        $this->lines[] = sprintf("CREATED:%s", date(C::$formats->ics_datetime, $this->last_modified));
        $this->lines[] = sprintf("LAST-MODIFIED:%s", date(C::$formats->ics_datetime, $this->last_modified));
        $this->lines[] = sprintf("DTSTAMP:%s", date(C::$formats->ics_datetime, $this->last_modified));
        $this->lines[] = "END:VEVENT";
    }
}
