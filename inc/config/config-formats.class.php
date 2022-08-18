<?php

namespace Feeds\Config;

class Config_Formats
{
    /**
     * Church Suite CSV import date time.
     *
     * @var string
     */
    public string $csv_import_datetime;

    /**
     * Display date format string.
     *
     * @var string
     */
    public string $display_date;

    /**
     * Display time format string.
     *
     * @var string
     */
    public string $display_time;

    /**
     * Sortable date format string.
     *
     * @var string
     */
    public string $sortable_date;

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
        $this->sortable_date = $config["sortable_date"];
    }
}
