<?php

namespace Feeds\ICal;

use DateTimeImmutable;
use Feeds\Config\Config as C;

class VEvent
{
    /**
     * Event unique ID.
     *
     * @var string
     */
    public readonly string $uid;

    /**
     * Start time.
     *
     * @var DateTimeImmutable
     */
    public readonly DateTimeImmutable $start;

    /**
     * End time.
     *
     * @var DateTimeImmutable
     */
    public readonly DateTimeImmutable $end;

    /**
     * Summary text.
     *
     * @var string
     */
    public readonly string $summary;

    /**
     * Optional description for more information.
     *
     * @var null|string
     */
    public readonly ?string $description;

    /**
     * Create VEvent.
     *
     * @param string $uid               Unique ID.
     * @param DateTimeImmutable $start  Start date time.
     * @param DateTimeImmutable $end    End date time.
     * @param string $summary           Event summary.
     * @param null|string $description  Optional extended event description.
     * @return void
     */
    public function __construct(string $uid, DateTimeImmutable $start, DateTimeImmutable $end, string $summary, ?string $description)
    {
        $this->uid = $uid;
        $this->start = $start;
        $this->end = $end;
        $this->summary = $summary;
        $this->description = $description;
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
        $lines[] = "BEGIN:VEVENT" .         "";
        $lines[] = "UID:" .                 $this->uid;
        $lines[] = "DTSTART;TZID=$tzid:" .  $this->start->format(C::$formats->ics_datetime);
        $lines[] = "DTEND;TZID=$tzid:" .    $this->end->format(C::$formats->ics_datetime);
        $lines[] = "SUMMARY:" .             $this->summary;
        $lines[] = "DESCRIPTION:" .         $this->description;
        $lines[] = "CREATED:" .             date(C::$formats->ics_datetime, $last_modified);
        $lines[] = "LAST-MODIFIED:" .       date(C::$formats->ics_datetime, $last_modified);
        $lines[] = "DTSTAMP:" .             date(C::$formats->ics_datetime, $last_modified);
        $lines[] = "END:VEVENT" .           "";
    }
}
