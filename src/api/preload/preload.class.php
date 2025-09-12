<?php

namespace Obadiah\Api\Preload;

use Obadiah\App;
use Obadiah\Preload\Preload as P;
use Obadiah\Response\Json;
use Obadiah\Router\Endpoint;

App::check();

class Preload extends Endpoint
{
    /**
     * GET: /api/preload
     *
     * @return Json
     */
    public function index_get(): Json
    {
        $results = array(
            // Bible Reading plan
            "bible" => P::get_bible_plan(),

            // Church Suite events
            "events" => P::get_events(),

            // Baserow lectionary
            "lectionary" => P::get_lectionary(),

            // People
            "people" => P::get_people(),

            // Refresh calendar
            "refresh" => P::get_refresh(),

            // Church Suite rota
            "rota" => P::get_rota()
        );

        // return JSON response
        return new Json($results);
    }
}
