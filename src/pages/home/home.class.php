<?php

namespace Feeds\Pages\Home;

use DateInterval;
use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Pages\Home\Index_Model;
use Feeds\Response\View;
use Feeds\Rota\Rota;

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
