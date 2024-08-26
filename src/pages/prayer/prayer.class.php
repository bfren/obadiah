<?php

namespace Obadiah\Pages\Prayer;

use DateTimeImmutable;
use Obadiah\Admin\Prayer_File;
use Obadiah\Admin\Result;
use Obadiah\App;
use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Prayer\Month;
use Obadiah\Prayer\Prayer_Calendar;
use Obadiah\Request\Request;
use Obadiah\Response\Action;
use Obadiah\Response\View;
use Obadiah\Router\Endpoint;
use Throwable;

App::check();

class Prayer extends Endpoint
{
    /**
     * Result object.
     *
     * @var Result|null
     */
    private ?Result $result = null;

    /**
     * GET: /prayer
     *
     * @return View
     */
    public function index_get(): View
    {
        $next_month = new DateTimeImmutable("next month");
        return new View("prayer", model: new Index_Model(
            result: $this->result,
            months: Prayer_Calendar::get_months(),
            next_month: $next_month->format(C::$formats->prayer_month_id)
        ));
    }

    /**
     * GET: /prayer/edit
     *
     * @return Action
     */
    public function edit_get(): Action
    {
        // define variables
        $people_per_day = (int)round(count(Cache::get_people()) / Month::MAX_DAYS, 1);

        // get template month (will pre-populate the days with this month's data)
        $from_id = Request::$get->string("from");
        if ($from_id) {
            $from = Month::load($from_id);
        } else {
            $from = Month::get_most_recent();
        }

        // the day for loop begins with 1 not 0 so we need an empty array item to push everything up one place
        $from_days = array_merge([""], array_values($from->days));
        $from_people = $from->people;

        // get the month this calendar is for
        $for_id = Request::$get->string("for");
        if (!$for_id) {
            $this->result = Result::failure("You must set the month this calendar is for.");
            return $this->index_get();
        }

        // parse the month as a date
        try {
            $for = new DateTimeImmutable(sprintf("%s-01", $for_id));
        } catch (Throwable $th) {
            _l_throwable($th);
            $this->result = Result::failure("Unable to determine the month this calendar is for.");
            return $this->index_get();
        }

        // create edit view
        return new View("prayer", name: "edit", model: new Edit_Model(
            result: $this->result,
            for: $for->modify("first day of"),
            days: $from_days,
            people: $from_people,
            per_day: $people_per_day
        ));
    }

    /**
     * GET: /prayer/delete
     *
     * @return View
     */
    public function delete_get(): View
    {
        // get file and delete
        if ($file = Request::$get->string("file")) {
            $this->result = Prayer_File::delete($file);
        }

        // return index page
        return $this->index_get();
    }
}
