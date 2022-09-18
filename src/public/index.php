<?php

namespace Feeds;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;
use Feeds\Response\Response;
use SplFileInfo;

// initialise app
require_once "../app.class.php";
App::init();

// check auth
Request::$session->is_authorised || Response::redirect("/login.php", true);

// get requested page
$uri = explode("/", Request::$uri);
$parts = Arr::match($uri);
$page = Arr::get($parts, 0);
$action = Arr::get($parts, 1);

// output requested page, or home by default
$path = sprintf("%s/pages/%s.php", C::$dir->cwd, $page);
$file = new SplFileInfo($path);
if (!$file->isFile()) {
    $path = sprintf("%s/pages/home.php", C::$dir->cwd);
}

require_once $path;
