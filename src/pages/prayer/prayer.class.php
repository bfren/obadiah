<?php

namespace Feeds\Pages\Prayer;

use DateTimeImmutable;
use Feeds\Admin\Prayer_File;
use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Prayer\Month;
use Feeds\Request\Request;
use Feeds\Response\Action;
use Feeds\Response\Redirect;
use Feeds\Response\View;
use Throwable;

App::check();

class Prayer
{
    /**
     * Result object.
     *
     * @var null|Result
     */
    private ?Result $result = null;

    /**
     * GET: /prayer
     *
     * @return View
     */
    public function index_get(): View
    {
        return new View("prayer", model: new Index_Model(
            result: $this->result,
            months: Cache::get_prayer_calendar()->get_months()
        ));
    }

    /**
     * GET: /prayer/edit
     *
     * @return Action
     */
    public function edit_get(): Action
    {
        // ensure the user has admin access
        if (!Request::$session->is_admin) {
            return new Redirect("/prayer");
        }

        // get prayer calendar
        $prayer_calendar = Cache::get_prayer_calendar();

        // define variables
        $people_per_day = (int)round(count($prayer_calendar->people) / Month::MAX_DAYS, 1);

        // get template month (will pre-populate the days with this month's data)
        $from_id = Request::$get->string("from");
        if ($from_id) {
            $from = Month::load($from_id);
        } else {
            $from = Month::get_most_recent();
        }

        // the day for loop begins with 1 not 0 so we need an empty array item to push everything up one place
        $from_days = array_merge(array(""), array_values($from->days));
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
            $this->result = Result::failure("Unable to determine the month this calendar is for.");
            return $this->index_get();
        }

        return new View("prayer", name: "edit", model: new Edit_Model(
            result: $this->result,
            prayer: $prayer_calendar,
            for: $for->modify("first day of"),
            days: $from_days,
            people: $from_people,
            per_day: $people_per_day
        ));
    }

    public function delete_get(): View
    {
        // get file and delete
        if ($file = Request::$get->string("file")) {
            $this->result = Prayer_File::delete($file);
        }

        // return index page
        return $this->index_get();
    }

    public function print_get(): View
    {
        // get requested month
        $month_id = Request::$get->string("month");
        $month = Month::load($month_id);

        return new View("prayer", name: "print", model: new Print_Model(
            month: $month
        ));
    }
}
