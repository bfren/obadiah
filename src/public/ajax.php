<?php

namespace Feeds\Pages;

use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Json\Action;
use Feeds\Json\Json;
use Feeds\Prayer\Month;
use Feeds\Request\Request;

// initialise app
require_once("../app.class.php");
App::init();

// check auth
if (!Request::is_admin()) {
    Json::output(Result::failure("Unauthorised."), 401);
}

// get input
$input = file_get_contents("php://input");
if (!$input) {
    Json::output(Result::failure("No input."), 400);
}

// decode action
$action = Action::decode($input);
if ($action == null) {
    Json::output(Result::failure("Invalid request."), 400);
}

// dispatch action
$result = match ($action->name) {
    "month" => Month::save($action->data),
    default => Result::failure("Unknown action.", 400)
};

// output result as JSON
Json::output($result);
