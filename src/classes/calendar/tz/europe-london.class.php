<?php

namespace Obadiah\Calendar\TZ;

use Obadiah\App;
use Obadiah\Calendar\Timezone;

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
        return array(
            "BEGIN:VTIMEZONE",
            "TZID:Europe/London",
            "BEGIN:STANDARD",
            "TZNAME:GMT",
            "DTSTART:19710101T020000",
            "TZOFFSETFROM:+0100",
            "TZOFFSETTO:+0000",
            "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU",
            "END:STANDARD",
            "BEGIN:DAYLIGHT",
            "TZNAME:BST",
            "DTSTART:19710101T010000",
            "TZOFFSETFROM:+0000",
            "TZOFFSETTO:+0100",
            "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU",
            "END:DAYLIGHT",
            "END:VTIMEZONE"
        );
    }
}
