<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Calendar\JEvent;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Json\Json;

// initialise app
require_once("../app.class.php");
App::init();

// get query options
$query = array(
    "date_start" => $_GET["start"],
    "date_end" => $_GET["end"]
);

// setup curl
$url = sprintf("https://%s.churchsuite.com/embed/calendar/json?%s", C::$general->church_suite_org, http_build_query($query));
$ch = curl_init($url);

// get calendar JSON
$json = curl_exec($ch);
if(!$json) {
    Json::output(array("error" => curl_error($ch)));
}

// build events array
$events = array();
$result = json_decode($json, true);
foreach ($result as $event) {
    $events[] = new JEvent(
        id: Arr::get($event, "id"),
        start: Arr::get($event, "datetime_start"),
        end: Arr::get($event, "datetime_end"),
        title: $event["name"],
        description: $event["description"]
    );
}

// output JSON
Json::output($events);
