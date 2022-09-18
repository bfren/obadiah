<?php

namespace Feeds\Pages;

use Feeds\Admin\Prayer_File;
use Feeds\App;
use Feeds\Request\Request;
use Feeds\Response\Response;

App::check();

// handle actions
if ($delete_month = Request::$get->string("delete_month")) {
    $result = Prayer_File::delete($delete_month);
    Response::redirect("/prayer");
}

// get action page
$action_page = match($action) {
    "edit" => "prayer-edit.php",
    "print" => "prayer-print.php",
    default => "prayer-list.php"
};

// load action page
require_once $action_page;
