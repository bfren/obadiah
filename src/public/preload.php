<?php

namespace Feeds;

use Feeds\Cache\Cache;
use Feeds\Json\Json;
use Feeds\Lectionary\Lectionary;
use Feeds\Prayer\Prayer_Calendar;
use Feeds\Refresh\Bible_Plan;
use Feeds\Rota\Rota;

// initialise app
require_once("../app.class.php");
App::init();

// preload caches
Cache::get_bible_plan(fn () => new Bible_Plan(), true);
Cache::get_lectionary(fn () => new Lectionary(), true);
Cache::get_prayer_calendar(fn () => new Prayer_Calendar(), true);
Cache::get_rota(fn () => new Rota(), true);

// clear events cache
Cache::clear_events();

// output simple respone
Json::output("OK");
