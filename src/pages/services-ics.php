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
            Builder::get_uid($rota->last_modified_timestamp, $c_service),
            $c_service->start,
            $c_service->end,
            Builder::get_summary($c_service),
            Builder::get_description($c_day, $c_service, false)
        );
    }
}

// create and output calendar
$vcal = new VCal($events, $rota->last_modified_timestamp);
$vcal->send_headers("services");
$vcal->print_output();
