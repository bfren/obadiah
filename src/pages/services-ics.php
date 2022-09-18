<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Calendar\VCal;
use Feeds\Calendar\VEvent;
use Feeds\Rota\Builder;

App::check();

/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var \Feeds\Rota\Rota $rota */

// add each service
$events = array();
foreach ($combined_days as $c_day) {
    foreach ($c_day->services as $c_service) {
        $events[] = new VEvent(
            uid: VEvent::get_uid($rota->last_modified_timestamp),
            start: $c_service->start,
            end: $c_service->end,
            summary: Builder::get_summary($c_service),
            description: Builder::get_description($c_day, $c_service, false)
        );
    }
}

// create and output calendar
$vcal = new VCal($events, $rota->last_modified_timestamp);
$vcal->send_headers("services");
$vcal->print_output();
