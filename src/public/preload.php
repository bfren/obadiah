<?php

namespace Feeds;

use Feeds\Cache\Cache;
use Feeds\Json\Json;

// initialise app
require_once "../app.class.php";
App::init();

// preload caches
Cache::get_bible_plan(true);
Cache::get_lectionary(true);
Cache::get_prayer_calendar(true);
Cache::get_refresh(true);
Cache::get_rota(true);

// clear events cache
Cache::clear_events();

// output simple respone
Json::output("OK");
