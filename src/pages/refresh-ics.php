<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Calendar\VCal;
use Feeds\Calendar\VEvent;

App::check();

/** @var \Feeds\Refresh\Refresh $refresh */

// add each service
$events = array();
foreach ($refresh->days as $day) {
    $events[] = new VEvent(
        uid: VEvent::get_uid(time()),
        start: $day->date,
        end: $day->date,
        summary: $day->get_summary(),
        description: $day->get_description(),
        is_all_day: true
    );
}

// create and output calendar
$vcal = new VCal($events, time());
$vcal->send_headers("refresh");
$vcal->print_output();
