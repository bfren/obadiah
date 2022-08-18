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
        $this->sortable_date = $config["sortable_date"];
    }
}
