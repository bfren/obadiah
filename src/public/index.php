<?php

namespace Feeds;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;
use Feeds\Response\Response;
use Feeds\Router\Router;
use SplFileInfo;

// initialise app
require_once "../app.class.php";
App::init();

// map routes
Router::map("login", "Login");

// check auth
Request::$session->is_authorised || Response::redirect("/login.php", true);

// get requested page
$uri = explode("/", Request::$uri);
$parts = Arr::match($uri);
$page = Arr::get($parts, 0, "home");
$action = Arr::get($parts, 1, "index");

$action = Router::dispatch($page, $action);
print_r($view);
$view->execute();exit;

// output requested page, or home by default
$path = sprintf("%s/pages/%s.php", C::$dir->cwd, $page);
$file = new SplFileInfo($path);
if (!$file->isFile()) {
    $path = sprintf("%s/pages/home.php", C::$dir->cwd);
}

require_once $path;
