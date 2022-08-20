<?php

namespace Feeds\Pages;

use DateInterval;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;
use Feeds\Rota\Builder;

defined("IDX") || die("Nice try.");

/** @var \Feeds\Rota\Builder $builder */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var \Feeds\Rota\Rota $rota */
/** @var array $filters */

// holds all the services so we can encode them as JSON later
$services = [];

// add each service
$interval = new DateInterval(sprintf("PT%sM", C::$events->length_in_minutes));
foreach ($combined_days as $date => $c_day) {
    foreach ($c_day->services as $c_service) {
        $services[] = array(
            "id" =>             Builder::get_uuid($c_service),
            "title" =>          Builder::get_summary($c_service, Arr::get($filters, "person") ?: ""),
            "start" =>          $c_service->start->format(C::$formats->json_datetime),
            "end" =>            $c_service->end->format(C::$formats->json_datetime),
            "description" =>    Builder::get_description($c_day, $c_service),
            "editable" =>       false
        );
    }
}

// output JSON headers
if (Request::$debug) {
    header("Content-Type: text/plain");
} else {
    header("Content-Type: text/json; charset=utf-8");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $rota->last_modified_timestamp));
}

// Output JSON
exit(json_encode($services));
