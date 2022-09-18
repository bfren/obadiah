<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Calendar\JEvent;
use Feeds\Config\Config as C;
use Feeds\Json\Json;

App::check();

/** @var \Feeds\Refresh\Refresh $refresh */

// add each service
$events = array();
foreach ($refresh->days as $day) {
    $events[] = new JEvent(
        id: JEvent::get_id(time()),
        start: $day->date->format(C::$formats->json_datetime),
        end: $day->date->format(C::$formats->json_datetime),
        title: $day->get_summary(),
        description: $day->get_description(),
        is_all_day: true
    );
}

// create and output calendar
Json::output($events, last_modified: time());
