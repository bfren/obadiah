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
     * Display day.
     *
     * @var string
     */
    public readonly string $display_day;

    /**
     * Display month.
     *
     * @var string
     */
    public readonly string $display_month;

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
     * Prayer Calendar month ID.
     *
     * @var string
     */
    public readonly string $prayer_month_id;

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
        $this->display_day = $config["display_day"];
        $this->display_month = $config["display_month"];
        $this->display_time = $config["display_time"];
        $this->ics_datetime = $config["ics_datetime"];
        $this->json_datetime = $config["json_datetime"];
        $this->prayer_month_id = $config["prayer_month_id"];
        $this->sortable_date = $config["sortable_date"];
        $this->sortable_datetime = $config["sortable_datetime"];
    }
}
