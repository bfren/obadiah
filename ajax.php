<?php

namespace Feeds\Pages;

use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Prayer\Month;
use Feeds\Request\Request;

// initialise app
require_once("classes/app.class.php");
App::init(__DIR__);

// check auth
Request::is_admin() || Request::redirect("/logout.php");

// handle actions
if (Request::$method == "POST") {
    // decode JSON
    $json = json_decode(file_get_contents("php://input"));

    // dispatch action
    $result = match($json->action) {
        "month" => Month::save($json->data)
    };
} else {
    die("Nice try.");
}

// output result as JSON
header("Content-Type: application/json");
echo json_encode($result ?: Result::failure("Something has gone wrong, please try again."));
exit;
