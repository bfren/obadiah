<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Rota\Builder;

// initialise app
require_once("../app.class.php");
App::init();

// get lectionary and rota
$lectionary = Cache::get_lectionary();
$rota = Cache::get_rota();

// apply get string as filters
$services = $rota->apply_filters($_GET, $lectionary);

// build rota
$combined_days = Builder::build_combined_rota($lectionary, $services);

// get format script
$format = match ($_GET["format"]) {
    "ics" => "services-ics.php",
    "json" => "services-json.php",
    default => "login.php"
};
$path = sprintf("%s/pages/%s", C::$dir->cwd, $format);

// load format
require_once($path);
