<?php

namespace Feeds\Pages;

use Feeds\App;

App::check();

// get action page
$action_page = match($action) {
    "month" => "prayer-month.php",
    default => "prayer-list.php"
};

// load action page
require_once($action_page);
