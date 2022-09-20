<?php

namespace Feeds\Calendar;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Request\Request;

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
     * @param bool $is_all_day          Whether or not the event is an all day event.
     * @return void
     */
    public function __construct(
        public readonly string $uid,
        public readonly DateTimeImmutable $start,
        public readonly DateTimeImmutable $end,
        public readonly string $summary,
        public readonly ?string $description,
        public readonly bool $is_all_day = false
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

        if ($this->is_all_day) {
            $lines[] = sprintf("DTSTART;TZID=%s;VALUE=DATE:%s", $tzid, $this->start->format(C::$formats->ics_date));
            $lines[] = "TRANSP:TRANSPARENT";
        } else {
            $lines[] = sprintf("DTSTART;TZID=%s:%s", $tzid, $this->start->format(C::$formats->ics_datetime));
            $lines[] = sprintf("DTEND;TZID=%s:%s", $tzid, $this->end->format(C::$formats->ics_datetime));
        }

        $lines[] = sprintf("SUMMARY:%s", $this->summary);
        $lines[] = sprintf("DESCRIPTION:%s", $this->description);
        $lines[] = sprintf("CREATED:%s", date(C::$formats->ics_datetime, $last_modified));
        $lines[] = sprintf("LAST-MODIFIED:%s", date(C::$formats->ics_datetime, $last_modified));
        $lines[] = sprintf("DTSTAMP:%s", date(C::$formats->ics_datetime, $last_modified));
        $lines[] = "END:VEVENT";
    }

    /**
     * Generate a unique ID for an event.
     *
     * @param int $last_modified        Calendar last modified date.
     * @return string                   Unique hashed ID.
     */
    public static function get_uid(int $last_modified): string
    {
        static $count = 0;
        $date = date(C::$formats->ics_datetime, $last_modified);
        $ip = Request::$server->string("REMOTE_ADDR");
        return sprintf("%06d-%s@%s", $count++, $date, $ip);
    }
}
