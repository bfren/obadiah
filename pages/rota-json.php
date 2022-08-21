<?php

namespace Feeds\Pages;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;
use Feeds\Rota\Builder;
use stdClass;

defined("IDX") || die("Nice try.");

/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var \Feeds\Rota\Rota $rota */
/** @var array $filters */

// holds all the services so we can encode them as JSON later
$services = [];

// add each service
foreach ($combined_days as $c_day) {
    foreach ($c_day->services as $c_service) {
        $services[] = array(
            "id" =>             Builder::get_uid($c_service),
            "start" =>          $c_service->start->format(C::$formats->json_datetime),
            "end" =>            $c_service->end->format(C::$formats->json_datetime),
            "title" =>          Builder::get_summary($c_service, Arr::get($filters, "person", "")),
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
    header(sprintf("Last-Modified: %s", gmdate("D, d M Y H:i:s", $rota->last_modified_timestamp)));
}

// Output JSON
unset($filters["api"]);
$json = new stdClass();
$json->filters = $filters;
$json->events = $services;
exit(json_encode($json));
