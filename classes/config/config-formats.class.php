<?php

namespace Feeds\Config;

use Feeds\App;

App::check();

class Config_Formats
{
    /**
     * Church Suite CSV import date time.
     *
     * @var string
     */
    public readonly string $csv_import_datetime;

    /**
     * Display date.
     *
     * @var string
     */
    public readonly string $display_date;

    /**
     * Display time.
     *
     * @var string
     */
    public readonly string $display_time;

    /**
     * ICS date time.
     *
     * @var string
     */
    public readonly string $ics_datetime;

    /**
     * JSON date time.
     *
     * @var string
     */
    public readonly string $json_datetime;

    /**
     * Sortable date.
     *
     * @var string
     */
    public readonly string $sortable_date;

    /**
     * Sortable date time.
     *
     * @var string
     */
    public readonly string $sortable_datetime;

    /**
     * Get values from formats configuration array.
     *
     * @param array $config             Formats configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->csv_import_datetime = $config["csv_import_datetime"];
        $this->display_date = $config["display_date"];
        $this->display_time = $config["display_time"];
        $this->ics_datetime = $config["ics_datetime"];
        $this->json_datetime = $config["json_datetime"];
        $this->sortable_date = $config["sortable_date"];
        $this->sortable_datetime = $config["sortable_datetime"];
    }
}
