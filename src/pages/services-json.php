<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Calendar\JEvent;
use Feeds\Config\Config as C;
use Feeds\Json\Json;
use Feeds\Rota\Builder;

App::check();

/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var \Feeds\Rota\Rota $rota */

// holds all the services so we can encode them as JSON later
$services = [];

// add each service
foreach ($combined_days as $c_day) {
    foreach ($c_day->services as $c_service) {
        $services[] = new JEvent(
            id: JEvent::get_id($rota->last_modified_timestamp),
            start: $c_service->start->format(C::$formats->json_datetime),
            end: $c_service->end->format(C::$formats->json_datetime),
            title: Builder::get_summary($c_service),
            description: Builder::get_description($c_day, $c_service, false)
        );
    }
}

// output JSON
Json::output($services, last_modified: $rota->last_modified_timestamp);
