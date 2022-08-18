<?php

namespace Feeds\Pages;

use Feeds\Cache\Cache;
use Feeds\Config\Config;
use Feeds\Lectionary\Lectionary;
use Feeds\Rota\Rota;

defined("IDX") || die("Nice try.");

// create cache
$cache = new Cache(Config::$dir_cache, Config::$cache->duration_in_seconds);

// get rota
$rota = $cache->get_rota(function () use ($base) {
    return new Rota($base);
});

// get lectionary
$lectionary = $cache->get_lectionary(function () use ($base) {
    return new Lectionary($base);
});

// apply filters
$default_filters = array(
    "from" => date(Config::$formats->sortable_date),
    "to" => date(Config::$formats->sortable_date, strtotime("+" . Config::$rota->default_days . " days"))
);
$filters = array_merge($default_filters, $_GET);
$services = $rota->apply_filters($filters);

// get action page
$action_page = match($action) {
    "ics" => "rota-ics.php",
    "json" => "rota-json.php",
    default => "rota-html.php"
};

// load action page
require_once($action_page);
