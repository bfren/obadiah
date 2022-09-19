<?php

namespace Feeds\Response;

use Feeds\App;
use Feeds\Calendar\VCal;
use Feeds\Config\Config as C;
use Feeds\Request\Request;

App::check();

class ICalendar extends Action
{
    /**
     * Create ICalendar and add headers.
     *
     * @param string $filename          Calendar download filename.
     * @param VCal $model               Calendar model.
     * @return void
     */
    public function __construct(
        public readonly string $filename,
        public readonly VCal $model
    )
    {
        parent::__construct();

        // output as plain text on debug
        if (Request::$debug) {
            $this->add_header("Content-Type", "text/plain");
            return;
        }

        // add standard ICalendar headers
        $this->add_header("Content-Type", "text/calendar; charset=utf-8");
        $this->add_header("Content-Disposition", sprintf("attachment; filename=%s-%s.ics", C::$general->church_suite_org, $this->filename));
        $this->add_header("Last-Modified", gmdate("D, d M Y H:i:s", $this->model->last_modified));
    }

    /**
     * Execute the action, printing the calendar.
     *
     * @return void
     */
    public function execute(): void
    {
        $this->model->print_output();
    }
}
