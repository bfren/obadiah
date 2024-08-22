<?php

namespace Obadiah\Pages\Home;

use DateInterval;
use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Home\Index_Model;
use Obadiah\Response\View;
use Obadiah\Rota\Rota;

App::check();

class Home
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

        return new View("home", model: new Index_Model(
            this_week: $this_week,
            upcoming: Rota::upcoming_sundays(),
            refresh_print: $refresh_print,
            refresh_feed: $refresh_feed
        ));
    }
}
