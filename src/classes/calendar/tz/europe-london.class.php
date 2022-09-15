<?php

namespace Feeds\ICal\TZ;

use Feeds\App;
use Feeds\Calendar\Timezone;

App::check();

class Europe_London implements Timezone
{
    /**
     * Return timezone definition for Europe/London.
     *
     * @return string[]
     */
    public function get_definition(): array
    {
        $definition = array();
        $definition[] = "BEGIN:VTIMEZONE";
        $definition[] = "TZID:Europe/London";
        $definition[] = "BEGIN:STANDARD";
        $definition[] = "TZNAME:GMT";
        $definition[] = "DTSTART:19710101T020000";
        $definition[] = "TZOFFSETFROM:+0100";
        $definition[] = "TZOFFSETTO:+0000";
        $definition[] = "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU";
        $definition[] = "END:STANDARD";
        $definition[] = "BEGIN:DAYLIGHT";
        $definition[] = "TZNAME:BST";
        $definition[] = "DTSTART:19710101T010000";
        $definition[] = "TZOFFSETFROM:+0000";
        $definition[] = "TZOFFSETTO:+0100";
        $definition[] = "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU";
        $definition[] = "END:DAYLIGHT";
        $definition[] = "END:VTIMEZONE";
        return $definition;
    }
}
