<?php

namespace Feeds\Pages;

use Feeds\Helpers\Arr;
use Feeds\ICal\VCal;
use Feeds\ICal\VEvent;
use Feeds\Request\Request;
use Feeds\Rota\Builder;

defined("IDX") || die("Nice try.");

/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var \Feeds\Rota\Rota $rota */
/** @var array $filters */

// create calendar
$vcal = new VCal($rota->last_modified_timestamp);

// add each service
foreach ($combined_days as $c_day) {
    foreach ($c_day->services as $c_service) {
        $vcal->events[] = new VEvent(
            Builder::get_uid($c_service),
            $c_service->start,
            $c_service->end,
            Builder::get_summary($c_service, Arr::get($filters, "person", "")),
            Builder::get_description($c_day, $c_service)
        );
    }
}

// output calendar
$vcal->send_headers("rota", Request::$debug);
$vcal->print_output();
