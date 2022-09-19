<?php

namespace Feeds\Pages\Preload;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Response\Json;

App::check();

class Preload
{
    /**
     * GET: /preload
     *
     * @return Json
     */
    public function index_get(): Json
    {
        // preload caches
        Cache::get_bible_plan(true);
        Cache::get_lectionary(true);
        Cache::get_prayer_calendar(true);
        Cache::get_refresh(true);
        Cache::get_rota(true);

        // clear events cache
        Cache::clear_events();

        // return JSON response
        return new Json("OK");
    }
}
