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
     * Display date.
     *
     * @var string
     */
    public string $display_date;

    /**
     * Display time.
     *
     * @var string
     */
    public string $display_time;

    /**
     * ICS date time.
     *
     * @var string
     */
    public string $ics_datetime;

    /**
     * Sortable date.
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
        $this->ics_datetime = $config["ics_datetime"];
        $this->sortable_date = $config["sortable_date"];
    }
}
