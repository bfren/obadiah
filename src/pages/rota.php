<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Lectionary\Lectionary;
use Feeds\Rota\Builder;
use Feeds\Rota\Rota;

App::check();

/** @var string $action */

// get lectionary and rota
$lectionary = Cache::get_lectionary(fn() => new Lectionary());
$rota = Cache::get_rota(fn() => new Rota());

// get and apply filters
$default_filters = array(
    "start" => date(C::$formats->sortable_date),    // show rota from 'today' by default
    "max" => 10                                     // show a maximum number of services by default
);
$filters = array_merge($default_filters, $_GET);
$services = $rota->apply_filters($filters, $lectionary);

// build rota
$combined_days = Builder::build_combined_rota($lectionary, $services);

// get action page
$action_page = match($action) {
    "ics" => "rota-ics.php",
    "json" => "rota-json.php",
    "print" => "rota-print.php",
    default => "rota-html.php"
};

// load action page
require_once($action_page);
