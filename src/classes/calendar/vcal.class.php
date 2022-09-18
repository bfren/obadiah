<?php

namespace Feeds\Calendar;

use DateTimeZone;
use Feeds\App;
use Feeds\Calendar\TZ\Europe_London;
use Feeds\Config\Config as C;
use Feeds\Request\Request;

App::check();

class VCal
{
    /**
     * Holds each line of the calendar output.
     *
     * @var string[]
     */
    private array $lines = array();

    /**
     * Add calendar headers and timezone settings for Europe/London.
     *
     * @param VEvent[] $events          Array of events in this calendar.
     * @param int $last_modified        Timestamp when the calendar was last modified.
     * @return void
     */
    public function __construct(
        public readonly array $events,
        public readonly int $last_modified
    ) {
        // begin calendar definition
        $this->lines[] = "BEGIN:VCALENDAR";
        $this->lines[] = "VERSION:2.0";
        $this->lines[] = "PRODID:-//bfren.dev//NONSGML//EN";
        $this->lines[] = "CALSCALE:GREGORIAN";
        $this->lines[] = "X-PUBLISHED-TTL:PT1H";

        // add timezone definition
        $tz = $this->get_ical_timezone(C::$general->timezone)?->get_definition();
        $this->lines = array_merge($this->lines, $tz ?: array());
    }

    /**
     * Get ICal timezone definition, or null if not supported.
     *
     * @param DateTimeZone $timezone    Config timezone.
     * @return null|Timezone            ICal timezone.
     */
    private function get_ical_timezone(DateTimeZone $timezone): ?Timezone
    {
        $name = $timezone->getName();
        return match ($name) {
            "Europe/London" => new Europe_London(),
            default => null
        };
    }

    /**
     * Send headers (use before printing the output).
     *
     * @param string $filename          Added to Church Suite org to give the name of the downloaded calendar file.
     * @return void
     */
    public function send_headers(string $filename): void
    {
        if (Request::$debug) {
            header("Content-Type: text/plain");
            return;
        }

        header("Content-Type: text/calendar; charset=utf-8");
        header(sprintf("Content-Disposition: attachment; filename=%s-%s.ics", C::$general->church_suite_org, $filename));
        header(sprintf("Last-Modified: %s", gmdate("D, d M Y H:i:s", $this->last_modified)));
    }

    /**
     * Generate output, ensure no lines are longer than 75 characters, and print to output.
     *
     * @return void
     */
    public function print_output(): void
    {
        // add each event to $lines
        foreach ($this->events as $event) {
            $event->add_to_array($this->lines, $this->last_modified);
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
}
