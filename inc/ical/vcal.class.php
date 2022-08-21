<?php

namespace Feeds\ICal;

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
        $this->lines[] = "BEGIN:VCALENDAR";
        $this->lines[] = "VERSION:2.0";
        $this->lines[] = "PRODID:-//bcg|design//NONSGML CCSP//EN";
        $this->lines[] = "CALSCALE:GREGORIAN";
        $this->lines[] = "X-PUBLISHED-TTL:PT1H";
        $this->lines[] = "BEGIN:VTIMEZONE";
        $this->lines[] = "TZID:Europe/London";
        $this->lines[] = "BEGIN:STANDARD";
        $this->lines[] = "TZNAME:GMT";
        $this->lines[] = "DTSTART:19710101T020000";
        $this->lines[] = "TZOFFSETFROM:+0100";
        $this->lines[] = "TZOFFSETTO:+0000";
        $this->lines[] = "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU";
        $this->lines[] = "END:STANDARD";
        $this->lines[] = "BEGIN:DAYLIGHT";
        $this->lines[] = "TZNAME:BST";
        $this->lines[] = "DTSTART:19710101T010000";
        $this->lines[] = "TZOFFSETFROM:+0000";
        $this->lines[] = "TZOFFSETTO:+0100";
        $this->lines[] = "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU";
        $this->lines[] = "END:DAYLIGHT";
        $this->lines[] = "END:VTIMEZONE";
    }

    /**
     * Send headers (use before printing the output).
     *
     * @param string $filename          Added to 'ccsp-' to give the name of the downloaded calendar file.
     * @param bool $debug               If true, a text/plain header will be used so the calendar is displayed not downloaded.
     * @return void
     */
    public function send_headers(string $filename, bool $debug = false): void
    {
        if ($debug) {
            header("Content-Type: text/plain");
        } else {
            header("Content-Type: text/calendar; charset=utf-8");
            header(sprintf("Content-Disposition: attachment; filename=ccsp-%s.ics", $filename));
            header(sprintf("Last-Modified: %s", gmdate("D, d M Y H:i:s", $this->last_modified)));
        }
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
                $folded .= substr($str, 0, $max) . "\r\n ";

                // remove the first $max characters and go again
                $str = substr($str, $max);
            }

            // add the folded string to the ics string
            $ics .= $folded . "\r\n";
        }

        // output text
        echo $ics;
    }
}
