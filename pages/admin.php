<?php

namespace Feeds\Pages;

use Feeds\Admin\Prayer_File;
use Feeds\Admin\Rota_File;
use Feeds\App;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;

App::check();
Request::is_admin() || Request::redirect("/logout.php");

// handle actions
if (Request::$method == "POST") {
    $result = match (Arr::get($_POST, "submit")) {
        "prayer-adults" => Prayer_File::upload_adults(),
        "prayer-children" => Prayer_File::upload_children(),
        "rota" => Rota_File::upload()
    };
} elseif ($delete_rota = Arr::get($_GET, "delete_rota")) {
    $result = Rota_File::delete($delete_rota);
} elseif ($delete_prayer = Arr::get($_GET, "delete_prayer")) {
    $result = Prayer_File::delete($delete_prayer);
    Request::redirect("/prayer");
}

// get action page
$action_page = match($action) {
    "prayer" => "admin-prayer.php",
    default => "admin-upload.php"
};

// load action page
require_once($action_page);
