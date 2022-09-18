<?php

namespace Feeds;

use Feeds\Config\Config as C;
use SplFileInfo;

// initialise app
require_once "../app.class.php";
App::init();

// output login page, or die
$path = sprintf("%s/pages/login.php", C::$dir->cwd);
$file = new SplFileInfo($path);
$file->isFile() || App::die("Unable to find login page.");
require_once $path;
