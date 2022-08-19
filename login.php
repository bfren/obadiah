<?php

namespace Feeds;

use Feeds\Config\Config as C;
use Feeds\Request\Request;

// include autoloader
require_once("inc/autoload.php");

// load config, run preflight checks, etc.
Request::init(__DIR__);

// output login page, or die
$path = sprintf("%s/pages/login.php", C::$dir->cwd);
if (!file_exists($path)) {
    die("Unable to find login page.");
}

require_once($path);
