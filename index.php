<?php

namespace Feeds;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

// each PHP script checks if this is defined to ensure incorrect access is denied
define("IDX", true);

// automatically load class definitions from inc directory
spl_autoload_register(function ($class) {
    $path = sprintf("%s.class.php", str_replace(array("\\", "_"), array("/", "-"), $class));
    $inc = str_replace("feeds", "inc", strtolower($path));
    require_once($inc);
});

// load config, run preflight checks, etc.
C::load(__DIR__);

// get requested page
$uri = explode("/", C::$uri);
$parts = array_values(array_filter($uri));
$page = Arr::get($parts, 0);
$action = Arr::get($parts, 1);

// output requested page, or home by default
$path = sprintf("%s/pages/%s.php", C::$dir->cwd, $page);
if (!file_exists($path)) {
    $path = sprintf("%s/pages/home.php", C::$dir->cwd);
}

require_once($path);
