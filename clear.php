<?php

namespace Feeds;

use Feeds\Cache\Cache;
use Feeds\Request\Request;

// initialise app
require_once("classes/app.class.php");
App::init(__DIR__);

// clear caches
Cache::clear_lectionary();
Cache::clear_prayer_calendar();
Cache::clear_rota();

// redirect to main page
Request::redirect("/");
