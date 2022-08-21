<?php

namespace Feeds\ICal;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;

App::check();

class VEvent
{
    /**
     * Create VEvent.
     *
     * @param string $uid               Unique ID.
     * @param DateTimeImmutable $start  Event start date time.
     * @param DateTimeImmutable $end    Event end date time.
     * @param string $summary           Event summary.
     * @param null|string $description  Optional extended event description.
     * @return void
     */
    public function __construct(
        public readonly string $uid,
        public readonly DateTimeImmutable $start,
        public readonly DateTimeImmutable $end,
        public readonly string $summary,
        public readonly ?string $description
    ) {
    }

    /**
     * Output this event as additional array values.
     *
     * @param array $lines              Array to add output to.
     * @param int $last_modified        Calendar last modified timestamp.
     * @return void
     */
    public function add_to_array(array &$lines, int $last_modified): void
    {
        $tzid = $this->start->getTimezone()->getName();
        $lines[] = "BEGIN:VEVENT";
        $lines[] = sprintf("UID:%s", $this->uid);
        $lines[] = sprintf("DTSTART;TZID=%s:%s", $tzid, $this->start->format(C::$formats->ics_datetime));
        $lines[] = sprintf("DTEND;TZID=%s:%s", $tzid, $this->end->format(C::$formats->ics_datetime));
        $lines[] = sprintf("SUMMARY:%s", $this->summary);
        $lines[] = sprintf("DESCRIPTION:%s", $this->description);
        $lines[] = sprintf("CREATED:%s", date(C::$formats->ics_datetime, $last_modified));
        $lines[] = sprintf("LAST-MODIFIED:%s", date(C::$formats->ics_datetime, $last_modified));
        $lines[] = sprintf("DTSTAMP:%s", date(C::$formats->ics_datetime, $last_modified));
        $lines[] = "END:VEVENT";
    }
}
