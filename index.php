<?php

namespace Feeds;

use Feeds\Config\Config;

// each PHP script checks if this is defined to ensure incorrect access is denied
define("IDX", true);

// automatically load class definitions from inc directory
spl_autoload_register(function ($class) {
    $path = str_replace(array("\\", "_"), array("/", "-"), $class) . ".class.php";
    $inc = str_replace("feeds", "inc", strtolower($path));
    require_once($inc);
});

// load config, run preflight checks, etc.
Config::load(__DIR__);

// get requested page
$uri = explode("/", Helpers\Arr::get($_SERVER, "REQUEST_URI"));
$parts = array_values(array_filter($uri));
$page = Helpers\Arr::get($parts, 0);
$action = Helpers\Arr::get($parts, 1);

// output requested page, or home by default
$path = Config::$cwd . "/pages/$page.php";
if (!file_exists($path)) {
    $path = Config::$cwd . "/pages/home.php";
}

require_once($path);
