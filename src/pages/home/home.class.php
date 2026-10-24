<?php

namespace Obadiah\Pages\Home;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Events\Events;
use Obadiah\Pages\Home\Index_Model;
use Obadiah\Response\View;
use Obadiah\Rota\Rota;
use Obadiah\Router\Endpoint;

App::check();

class Home extends Endpoint
{
    /**
     * GET: /
     *
     * @return View
     */
    public function index_get(): View
    {
        $today = new DateTimeImmutable();
        $this_week = array(
            "start" => $today->format(C::$formats->sortable_date),
            "end" => $today->add(new DateInterval("P7D"))->format(C::$formats->sortable_date)
        );

        $refresh_print = array(
            "month" => $today->format(C::$formats->prayer_month_id)
        );

        $refresh_feed = array(
            "api" => C::$login->api
        );

        $stale_cache = new DateTime("now", C::$events->timezone);
        $stale_cache->sub(new DateInterval(sprintf("PT%sS", C::$cache->duration_in_seconds)));
        $check_cache = fn(int $timestamp) => $timestamp > $stale_cache->getTimestamp();

        return new View("home", model: new Index_Model(
            this_week: $this_week,
            upcoming: Rota::upcoming_sundays(),
            refresh_print: $refresh_print,
            refresh_feed: $refresh_feed,
            caches_check: array(
                "bible" => $check_cache(Cache::get_bible_plan_last_modified()),
                "events" => $check_cache(Cache::get_events_last_modified(Events::get_default_query())),
                "lectionary" => $check_cache(Cache::get_lectionary_last_modified()),
                "people" => $check_cache(Cache::get_people_last_modified()),
                "refresh" => $check_cache(Cache::get_refresh_last_modified()),
                "rota" => $check_cache(Cache::get_rota_last_modified())
            )
        ));
    }
}
