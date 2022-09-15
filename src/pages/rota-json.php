<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Json\Json;
use Feeds\Rota\Builder;
use Feeds\Wp\Service;
use stdClass;

App::check();

/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var \Feeds\Rota\Rota $rota */
/** @var array $filters */

// holds all the services so we can encode them as JSON later
$services = [];

// add each service
foreach ($combined_days as $c_day) {
    foreach ($c_day->services as $c_service) {
        $services[] = new Service(
            id: Builder::get_uid($rota->last_modified_timestamp, $c_service),
            start: $c_service->start->format(C::$formats->json_datetime),
            end: $c_service->end->format(C::$formats->json_datetime),
            title: Builder::get_summary($c_service, Arr::get($filters, "person")),
            description: Builder::get_description($c_day, $c_service)
        );
    }
}

// Remove api key so it is not included in the response
unset($filters["api"]);

// Build JSON response
$response = new stdClass();
$response->filters = $filters;
$response->services = $services;

// Output JSON
Json::output($response, last_modified: $rota->last_modified_timestamp);
