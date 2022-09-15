<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Lectionary\Lectionary;
use Feeds\Rota\Builder;
use Feeds\Rota\Rota;

// initialise app
require_once("../app.class.php");
App::init();

// get lectionary and rota
$lectionary = Cache::get_lectionary(fn () => new Lectionary());
$rota = Cache::get_rota(fn () => new Rota());

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
