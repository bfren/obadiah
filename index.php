<?php

namespace Feeds;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;

// include autoloader
require_once("inc/autoload.php");

// load config, run preflight checks, etc.
Request::init(__DIR__);

// check auth
Request::$auth || Request::redirect("/login.php");

// get requested page
$uri = explode("/", Request::$uri);
$parts = array_values(array_filter($uri));
$page = Arr::get($parts, 0);
$action = Arr::get($parts, 1);

// output requested page, or home by default
$path = sprintf("%s/pages/%s.php", C::$dir->cwd, $page);
if (!file_exists($path)) {
    $path = sprintf("%s/pages/home.php", C::$dir->cwd);
}

require_once($path);
