<?php

namespace Feeds\Pages;

use DateInterval;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;

defined("IDX") || die("Nice try.");

/** @var \Feeds\Rota\Builder $builder */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var \Feeds\Rota\Rota $rota */
/** @var array $filters */

// holds all the lines so we can ensure they are never too long later
$lines = [];

// add calendar headers and timezone settings
$lines[] = "BEGIN:VCALENDAR";
$lines[] = "VERSION:2.0";
$lines[] = "PRODID:-//bcg|design//NONSGML CCSP//EN";
$lines[] = "CALSCALE:GREGORIAN";
$lines[] = "X-PUBLISHED-TTL:PT1H";
$lines[] = "BEGIN:VTIMEZONE";
$lines[] = "TZID:" . C::$events->timezone;
$lines[] = "BEGIN:STANDARD";
$lines[] = "TZNAME:GMT";
$lines[] = "DTSTART:19710101T020000";
$lines[] = "TZOFFSETFROM:+0100";
$lines[] = "TZOFFSETTO:+0000";
$lines[] = "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU";
$lines[] = "END:STANDARD";
$lines[] = "BEGIN:DAYLIGHT";
$lines[] = "TZNAME:BST";
$lines[] = "DTSTART:19710101T010000";
$lines[] = "TZOFFSETFROM:+0000";
$lines[] = "TZOFFSETTO:+0100";
$lines[] = "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU";
$lines[] = "END:DAYLIGHT";
$lines[] = "END:VTIMEZONE";

// add each service
$interval = new DateInterval(sprintf("PT%sM", C::$events->length_in_minutes));
foreach ($combined_days as $date => $c_day) {
    foreach ($c_day->services as $c_service) {
        $tzid = $c_service->dt->getTimezone()->getName();
        $lines[] = "BEGIN:VEVENT" .         "";
        $lines[] = "UID:" .                 $builder->get_uuid($c_service);
        $lines[] = "DTSTART;TZID=$tzid:" .  $c_service->dt->format(C::$formats->ics_datetime);
        $lines[] = "DTEND;TZID=$tzid:" .    $c_service->dt->add($interval)->format(C::$formats->ics_datetime);
        $lines[] = "CREATED:" .             date(C::$formats->ics_datetime, $rota->last_modified_timestamp);
        $lines[] = "LAST-MODIFIED:" .       date(C::$formats->ics_datetime, $rota->last_modified_timestamp);
        $lines[] = "DTSTAMP:" .             date(C::$formats->ics_datetime, $rota->last_modified_timestamp);
        $lines[] = "DESCRIPTION:" .         $builder->get_description($c_day, $c_service);
        $lines[] = "SUMMARY:" .             $builder->get_summary($c_service, Arr::get($filters, "person") ?: "");
        $lines[] = "END:VEVENT" .           "";
    }
}

// add calendar end
$lines[] = "END:VCALENDAR";

// ensure no lines are longer than $max characters
$ics = "";
$max = 74;
foreach ($lines as $line) {
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

// output ICS headers
if (Request::$debug) {
    header("Content-Type: text/plain");
} else {
    header("Content-Type: text/calendar; charset=utf-8");
    header("Content-Disposition: attachment; filename=christ_church_selly_park_rota.ics");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $rota->last_modified_timestamp));
}

// Output ICS
exit($ics);
