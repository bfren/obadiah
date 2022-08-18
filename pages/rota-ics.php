<?php

namespace Feeds\Pages;

use Feeds\Config\Config as C;
use Feeds\Rota\Combined_Day;
use Feeds\Rota\Combined_Service;

defined("IDX") || die("Nice try.");

/** @var \Feeds\Rota\Rota $rota */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var array $filters */

// set the timezone string
$tzid = "Europe/London";

// holds all the lines so we can ensure they are never too long later
$lines = [];

// add calendar headers and timezone settings
$lines[] = "BEGIN:VCALENDAR";
$lines[] = "VERSION:2.0";
$lines[] = "PRODID:-//bcg|design//NONSGML CCSP//EN";
$lines[] = "CALSCALE:GREGORIAN";
$lines[] = "X-PUBLISHED-TTL:PT1H";
$lines[] = "BEGIN:VTIMEZONE";
$lines[] = "TZID:$tzid";
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
foreach ($combined_days as $date => $c_day) {
    foreach ($c_day->services as $c_service) {
        $lines[] = "BEGIN:VEVENT" .         "";
        $lines[] = "UID:" .                 get_uuid($c_service);
        $lines[] = "DTSTART;TZID=$tzid:" .  date(C::$formats->ics_datetime, $c_service->timestamp);
        $lines[] = "DTEND;TZID=$tzid:" .    date(C::$formats->ics_datetime, $c_service->timestamp + (60 * 60));
        $lines[] = "CREATED:" .             date(C::$formats->ics_datetime, $rota->last_modified_timestamp);
        $lines[] = "LAST-MODIFIED:" .       date(C::$formats->ics_datetime, $rota->last_modified_timestamp);
        $lines[] = "DTSTAMP:" .             date(C::$formats->ics_datetime, $rota->last_modified_timestamp);
        $lines[] = "DESCRIPTION:" .         get_description($c_day, $c_service);
        $lines[] = "SUMMARY:" .             get_summary($c_service, $filters["person"]);
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
if (!isset($_GET["debug"])) {
    header("Content-Type: text/calendar; charset=utf-8");
    header("Content-Disposition: attachment; filename=christ_church_selly_park_rota.ics");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $rota->last_modified_timestamp));
}

// Output ics
exit($ics);

/**
 * Generate a unique ID for a service.
 *
 * @param Combined_Service $service     Service object.
 * @return string                       Unique hashed ID.
 */
function get_uuid(Combined_Service $service)
{
    return md5($service->timestamp . $service->name);
}

/**
 * Generate an event summary for a service, including role indicators for the specified person.
 *
 * @param Combined_Service $service     Service object.
 * @param string $person                Selected person.
 * @return string                       Service name with role indicators.
 */
function get_summary(Combined_Service $service, string $person)
{
    // use the name as the basic summary
    $summary = $service->name;

    // if no person is set, return the summary
    if (!$person) {
        return $summary;
    }

    // look for certain roles
    $roles = array();
    foreach ($service->roles as $role => $people) {
        foreach ($people as $p) {
            if (str_starts_with($p, $person)) {
                $roles[] = match ($role) {
                    "Duty Warden" => "W",
                    "Intercessions" => "Py",
                    "Lead Musician" => "M",
                    "Leader" => "L",
                    "Preacher" => "Pr",
                    "President" => "Ps",
                    default => null
                };
            }
        }
    }

    // if there are no roles, return the summary
    $roles = array_filter($roles);
    if (!$roles) {
        return $summary;
    }

    // sort roles and add to summary
    sort($roles);
    return $summary . " (" . join(", ", $roles) . ")";
}

/**
 * Generate an event description for a service, including lectionary / teaching info and roles.
 *
 * @param Combined_Day $day             Lectionary day information.
 * @param Combined_Service $service     Service object.
 * @return string                       Event description.
 */
function get_description(Combined_Day $day, Combined_Service $service)
{
    // create empty array for description lines
    $description = array();

    // add lectionary info
    if ($day->name) {
        $description[] = "= Liturgical Day =";
        $description[] = $day->name;
        $description[] = "";
    }

    // add teaching
    if ($service->series_title || $service->sermon_title || $service->main_reading) {
        $description[] = "= Teaching =";

        // series title
        if ($service->series_title) {
            $title = $service->series_title;
            if ($service->sermon_num) {
                $title = $title . " (" . $service->sermon_num . ")";
            }
            $description[] = $title;
        }

        // sermon title
        if ($service->sermon_title) {
            $description[] = $service->sermon_title;
        }

        // main reading
        if ($service->main_reading) {
            $description[] = "Main Reading: " . $service->main_reading;
        }

        // additional reading
        if ($service->additional_reading) {
            $description[] = "Additional Reading: " . $service->additional_reading;
        }

        $description[] = "";
    }

    // add roles
    if ($service->roles) {
        $description[] = "= Roles =";
        foreach ($service->roles as $role => $people) {
            $description[] = $role . ": " . join(", ", $people);
        }
    }

    // return description
    return join("\\n", $description);
}
