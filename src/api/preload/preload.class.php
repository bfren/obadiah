<?php

namespace Obadiah\Api\Preload;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Events\Events;
use Obadiah\Response\Json;
use Obadiah\Router\Endpoint;
use Throwable;

App::check();

class Preload extends Endpoint
{
    /**
     * GET: /preload
     *
     * @return Json
     */
    public function index_get(): Json
    {
        $results = array(
            // Bible Reading plan
            "bible" => self::load(fn () => Cache::get_bible_plan(true)),

            // Church Suite events
            "events" => self::load(function () {
                // clear events cache
                Cache::clear_events();

                // build query to preload events for this month
                $today = new DateTimeImmutable(timezone: C::$events->timezone);
                $start_of_month = $today->modify("first day of")->format(C::$formats->sortable_date);
                $query = http_build_query(array("date_start" => $start_of_month));

                // get events
                Cache::get_events($query, fn ($q) => Events::get_events($q));
            }),

            // Baserow lectionary
            "lectionary" => self::load(fn () => Cache::get_lectionary(true)),

            // People
            "people" => self::load(fn () => Cache::get_people(true)),

            // Refresh calendar
            "refresh" => self::load(fn () => Cache::get_refresh(true)),

            // Church Suite rota
            "rota" => self::load(fn () => Cache::get_rota(true))
        );

        // return JSON response
        return new Json($results);
    }

    /**
     * Call a function and return the result plus execution time (or error reason).
     *
     * @param callable $callable        Callable function.
     * @return mixed[]                  OK if $callable executed successfully, Error if not.
     */
    private static function load(callable $callable): array
    {
        try {
            // call cache loader
            $start_time = microtime(true);
            call_user_func($callable);
            $end_time = microtime(true);

            // calculate time and return result
            $execution_time = ($end_time - $start_time);
            return array("result" => "OK", "time" => sprintf('%05.4fs', $execution_time));
        } catch (Throwable $th) {
            _l_throwable($th);
            return array("result" => "Error", "reason" => $th->getMessage());
        }
    }
}
