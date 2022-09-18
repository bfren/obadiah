<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Request\Request;
use Feeds\Rota\Builder;

// initialise app
require_once "../app.class.php";
App::init();

// get lectionary and rota
$lectionary = Cache::get_lectionary();
$rota = Cache::get_rota();

// get and apply filters
$default_filters = array(
    "start" => date(C::$formats->sortable_date) // show rota from 'today' by default
);
$filters = array_merge($default_filters, Request::$get->all());
$services = $rota->apply_filters($filters, $lectionary);

// build rota
$combined_days = Builder::build_combined_rota($lectionary, $services);

// get format script
$format = match (Request::$get->string("format")) {
    "ics" => "services-ics.php",
    "json" => "services-json.php",
    default => "login.php"
};
$path = sprintf("%s/pages/%s", C::$dir->cwd, $format);

// load format
require_once $path;
