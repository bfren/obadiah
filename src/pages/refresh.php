<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Cache\Cache;

App::check();

// get refresh
$refresh = Cache::get_refresh();

// get action page
$action_page = match($action) {
    "ics" => "refresh-ics.php",
    "json" => "refresh-json.php",
    default => "refresh-html.php"
};

// load action page
require_once $action_page;
