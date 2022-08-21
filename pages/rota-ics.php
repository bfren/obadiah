<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Helpers\Arr;
use Feeds\ICal\VCal;
use Feeds\ICal\VEvent;
use Feeds\Request\Request;
use Feeds\Rota\Builder;

App::check();

/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var \Feeds\Rota\Rota $rota */
/** @var array $filters */

// add each service
$events = array();
foreach ($combined_days as $c_day) {
    foreach ($c_day->services as $c_service) {
        $events[] = new VEvent(
            Builder::get_uid($c_service),
            $c_service->start,
            $c_service->end,
            Builder::get_summary($c_service, Arr::get($filters, "person", "")),
            Builder::get_description($c_day, $c_service)
        );
    }
}

// create and output calendar
$vcal = new VCal($events, $rota->last_modified_timestamp);
$vcal->send_headers("rota", Request::$debug);
$vcal->print_output();
