<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Request\Request;

App::check();

// get refresh
$refresh = Cache::get_refresh();

// get format script
$format = match (Request::$get->string("format")) {
    "ics" => "refresh-ics.php",
    "json" => "refresh-json.php",
    default => "login.php"
};
$path = sprintf("%s/pages/%s", C::$dir->cwd, $format);

// load format page
require_once $path;
