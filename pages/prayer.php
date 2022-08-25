<?php

namespace Feeds\Pages;

use Feeds\Admin\Prayer_File;
use Feeds\App;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;

App::check();

// handle actions
if ($delete_month = Arr::get($_GET, "delete_month")) {
    $result = Prayer_File::delete($delete_month);
    Request::redirect("/prayer");
}

// get action page
$action_page = match($action) {
    "edit" => "prayer-edit.php",
    "print" => "prayer-print.php",
    default => "prayer-list.php"
};

// load action page
require_once($action_page);
